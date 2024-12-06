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
        $startDate = Carbon::parse($request->get('start_date', now()->startOfMonth()->toDateString()))->startOfDay();
        $endDate = Carbon::parse($request->get('end_date', now()->endOfMonth()->toDateString()))->endOfDay();

        $salesPerStore = Store::select('Dim_Stores.Name')
            ->join('Fact_Orders', 'Dim_Stores.StoreID', '=', 'Fact_Orders.store_id')
            ->join('Dim_Products', 'Fact_Orders.product_id', '=', 'Dim_Products.productID')
            ->selectRaw('Dim_Stores.Name, SUM(Fact_Orders.qty * Fact_Orders.price) as total_sales')
            ->whereBetween('Fact_Orders.created_at', [$startDate, $endDate])
            ->groupBy('Dim_Stores.Name')
            ->get();

        
        $salesPerStoreLabels = $salesPerStore->pluck('Name')->toArray();
        $salesPerStoreData = $salesPerStore->pluck('total_sales')->toArray();

        $salesPerProduct = Product::select('Dim_Products.Name')
            ->join('Fact_Orders', 'Dim_Products.productID', '=', 'Fact_Orders.product_id')
            ->selectRaw('Dim_Products.Name, SUM(Fact_Orders.qty * Fact_Orders.price) as total_sales')
            ->groupBy('Dim_Products.Name')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        $salesPerProductLabels = $salesPerProduct->pluck('Name')->toArray();
        $salesPerProductData = $salesPerProduct->pluck('total_sales')->toArray();

        $salesByMonth = DB::table('Fact_Orders')
            ->selectRaw('YEAR(Fact_Orders.created_at) as year, MONTH(Fact_Orders.created_at) as month, SUM(Fact_Orders.qty * Dim_Products.Unit_price) as total_sales')
            ->join('Dim_Products', 'Fact_Orders.product_id', '=', 'Dim_Products.productID')
            ->whereBetween('Fact_Orders.created_at', [
                $startDate->format('Y-m-d H:i:s'), 
                $endDate->format('Y-m-d H:i:s')
            ])
            ->groupBy(DB::raw('YEAR(Fact_Orders.created_at), MONTH(Fact_Orders.created_at)'))
            ->orderBy(DB::raw('YEAR(Fact_Orders.created_at), MONTH(Fact_Orders.created_at)'))
            ->get();

        $allMonths = [];
        $monthlySales = [];

        $startMonth = $startDate->copy()->startOfMonth();
        $endMonth = $endDate->copy()->endOfMonth();

        $currentMonth = $startMonth;
        while ($currentMonth <= $endMonth) {
            $monthLabel = $currentMonth->format('Y-m');
            $allMonths[] = $monthLabel;

            $monthlySales[] = $salesByMonth->firstWhere(function ($sale) use ($currentMonth) {
                return $sale->year == $currentMonth->year && $sale->month == $currentMonth->month;
            })?->total_sales ?? 0;

            $currentMonth->addMonth();
        }

        $monthlyRevenues = DB::table('Fact_Orders as o')
            ->join('Dim_Stores as s', 'o.store_id', '=', 's.StoreID')
            ->join('Dim_Products as p', 'o.product_id', '=', 'p.ProductKey')
            ->selectRaw('
                s.StoreID, 
                s.Name as StoreName, 
                YEAR(o.created_at) as Year, 
                MONTH(o.created_at) as Month, 
                SUM(o.qty * p.Unit_price) as MonthlyRevenue
            ')
            ->whereBetween('o.created_at', [$startDate, $endDate])
            ->groupBy('s.StoreID', 's.Name', DB::raw('YEAR(o.created_at)'), DB::raw('MONTH(o.created_at)'))
            ->orderBy(DB::raw('YEAR(o.created_at)'))
            ->orderBy(DB::raw('MONTH(o.created_at)'))
            ->orderBy('s.StoreID')
            ->get();

        $months = $monthlyRevenues->map(fn($row) => sprintf('%d-%02d', $row->Year, $row->Month))->unique()->values();
        $stores = $monthlyRevenues->pluck('StoreName')->unique();

        $groupedDatasets = $stores->map(function ($storeName) use ($monthlyRevenues, $months) {
            $storeData = $monthlyRevenues->where('StoreName', $storeName);

            $data = $months->map(function ($month) use ($storeData) {
                $revenue = $storeData->firstWhere(fn($row) => sprintf('%d-%02d', $row->Year, $row->Month) === $month);
                return $revenue ? $revenue->MonthlyRevenue : 0;
            });

            return [
                'label' => $storeName,
                'data' => $data,
                'backgroundColor' => sprintf(
                    'rgba(%d, %d, %d, 0.7)',
                    rand(50, 255),
                    rand(50, 255),
                    rand(50, 255)
                ),
            ];
        });

        $paymentPreferences = DB::table('Fact_Orders')
            ->select('payment_type', DB::raw('COUNT(*) as TotalPurchases'))
            ->groupBy('payment_type')
            ->whereBetween('Fact_Orders.created_at', [$startDate, $endDate])
            ->get();

        $topCustomers = DB::table('Fact_Orders')
            ->join('Dim_Customers', 'Fact_Orders.customer_id', '=', 'Dim_Customers.CustomerID')
            ->selectRaw('Dim_Customers.Name, SUM(Fact_Orders.qty * Fact_Orders.price) as TotalSpent')
            ->groupBy('Dim_Customers.Name')
            ->orderBy('TotalSpent', 'DESC')
            ->whereBetween('Fact_Orders.created_at', [$startDate, $endDate])
            ->limit(10)
            ->get();


        $subQuery = DB::table('Fact_Orders as o')
            ->join('Dim_Customers as c', 'o.customer_id', '=', 'c.CustomerID')
            ->join('Dim_Products as p', 'o.product_id', '=', 'p.ProductID')
            ->select(
                'c.Name as CustomerName',
                'p.Name as ProductName',
                DB::raw('SUM(o.qty) as TotalQuantity'),
                DB::raw('ROW_NUMBER() OVER (PARTITION BY c.Name ORDER BY SUM(o.qty) DESC) as Rank')
            )
            ->whereBetween('o.created_at', [$startDate, $endDate])
            ->groupBy('c.Name', 'p.Name');
    
         $topProductsPerCustomer = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery)
            ->where('Rank', '<=', 3)
            ->orderBy('CustomerName')
            ->orderBy('Rank')
            ->paginate(10);
    
    

        return view('dashboard', compact(
            'salesPerStoreLabels',
            'salesPerStoreData',
            'salesPerProductLabels',
            'salesPerProductData',
            'allMonths',
            'monthlySales',
            'startDate',
            'endDate',
            'monthlyRevenues',
            'months',
            'groupedDatasets',
            'paymentPreferences',
            'topCustomers',
            'topProductsPerCustomer'
        ));
    }
}
