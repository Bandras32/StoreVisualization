<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $orders = Order::with(['customer', 'product', 'store'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('customer', function ($query) use ($search) {
                        $query->where('Name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('product', function ($query) use ($search) {
                        $query->where('Name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('store', function ($query) use ($search) {
                        $query->where('Name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
