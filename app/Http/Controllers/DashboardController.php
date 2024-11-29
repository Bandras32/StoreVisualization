<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the start and end dates from the request
        $startDate = Carbon::parse($request->get('start_date', now()->startOfMonth()->toDateString()))->startOfDay();
        $endDate = Carbon::parse($request->get('end_date', now()->endOfMonth()->toDateString()))->endOfDay();

        // Sales per Store with date range filter (optimized join)
        $salesPerStore = Store::select('Dim_Stores.Name')
            ->join('Fact_Orders', 'Dim_Stores.StoreID', '=', 'Fact_Orders.warehouse_id')
            ->join('Dim_Products', 'Fact_Orders.product_id', '=', 'Dim_Products.productID')
            ->selectRaw('Dim_Stores.Name, SUM(Fact_Orders.qty * Dim_Products.Unit_price) as total_sales')
            ->whereBetween('Fact_Orders.created_at', [$startDate, $endDate])
            ->groupBy('Dim_Stores.Name')
            ->get();

        // Prepare data for the dashboard
        $salesPerStoreLabels = $salesPerStore->pluck('Name')->toArray();
        $salesPerStoreData = $salesPerStore->pluck('total_sales')->toArray();

        // Sales per Product with a limit of the top 10
        $salesPerProduct = Product::select('Dim_Products.Name')
            ->join('Fact_Orders', 'Dim_Products.productID', '=', 'Fact_Orders.product_id')
            ->selectRaw('Dim_Products.Name, SUM(Fact_Orders.qty * Dim_Products.Unit_price) as total_sales')
            ->groupBy('Dim_Products.Name')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        // Prepare product data
        $salesPerProductLabels = $salesPerProduct->pluck('Name')->toArray();
        $salesPerProductData = $salesPerProduct->pluck('total_sales')->toArray();

        // Fetch sales data by month for the given date range with optimized query
        $salesByMonth = DB::table('Fact_Orders')
            ->selectRaw('YEAR(Fact_Orders.created_at) as year, MONTH(Fact_Orders.created_at) as month, SUM(Fact_Orders.qty * Dim_Products.Unit_price) as total_sales')
            ->join('Dim_Products', 'Fact_Orders.product_id', '=', 'Dim_Products.productID')
            ->whereBetween(DB::raw('CAST(Fact_Orders.created_at AS DATE)'), [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy(DB::raw('YEAR(Fact_Orders.created_at), MONTH(Fact_Orders.created_at)'))
            ->orderBy(DB::raw('YEAR(Fact_Orders.created_at), MONTH(Fact_Orders.created_at)'))
            ->get();

        // Prepare monthly sales data (remove the loop and optimize by matching with grouped data)
        $allMonths = [];
        $monthlySales = [];

        $startMonth = $startDate->copy()->startOfMonth();
        $endMonth = $endDate->copy()->endOfMonth();

        // Loop through months but directly reference sales data
        $currentMonth = $startMonth;
        while ($currentMonth <= $endMonth) {
            $monthLabel = $currentMonth->format('Y-m');
            $allMonths[] = $monthLabel;

            $monthlySales[] = $salesByMonth->firstWhere(function($sale) use ($currentMonth) {
                return $sale->year == $currentMonth->year && $sale->month == $currentMonth->month;
            })?->total_sales ?? 0;

            $currentMonth->addMonth();
        }

        // Top 3 Stores by Total Sales (optimized join)
        $topStores = Store::select('Dim_Stores.Name')
            ->join('Fact_Orders', 'Dim_Stores.StoreID', '=', 'Fact_Orders.warehouse_id')
            ->join('Dim_Products', 'Fact_Orders.product_id', '=', 'Dim_Products.productID')
            ->selectRaw('Dim_Stores.Name, SUM(Fact_Orders.qty * Dim_Products.Unit_price) as total_sales')
            ->whereBetween('Fact_Orders.created_at', [$startDate, $endDate])
            ->groupBy('Dim_Stores.Name')
            ->orderByDesc('total_sales')
            ->limit(3)
            ->get();

        $topStoresLabels = $topStores->pluck('Name')->toArray();

        // Seasonal Sales for Top 3 Stores (optimize this part by using batch queries)
        $seasons = [
            'Winter' => [12, 1, 2],
            'Spring' => [3, 4, 5],
            'Summer' => [6, 7, 8],
            'Fall'   => [9, 10, 11],
        ];

        $seasonalSales = [];
        foreach ($topStoresLabels as $storeName) {
            $storeSales = [];
            foreach ($seasons as $season => $months) {
                $sales = DB::table('Fact_Orders')
                    ->join('Dim_Products', 'Fact_Orders.product_id', '=', 'Dim_Products.productID')
                    ->join('Dim_Stores', 'Fact_Orders.warehouse_id', '=', 'Dim_Stores.StoreID')
                    ->where('Dim_Stores.Name', $storeName)
                    ->whereBetween('Fact_Orders.created_at', [$startDate, $endDate])
                    ->whereIn(DB::raw('MONTH(Fact_Orders.created_at)'), $months)
                    ->selectRaw('SUM(Fact_Orders.qty * Dim_Products.Unit_price) as total_sales')
                    ->value('total_sales');
                $storeSales[$season] = $sales ?? 0;
            }
            $seasonalSales[] = $storeSales;
        }

        // Return view with optimized data
        return view('dashboard', compact(
            'salesPerStoreLabels',
            'salesPerStoreData',
            'salesPerProductLabels',
            'salesPerProductData',
            'allMonths',
            'monthlySales',
            'startDate',
            'endDate',
            'topStoresLabels',
            'seasonalSales'
        ));
    }
}
