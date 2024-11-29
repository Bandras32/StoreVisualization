<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $products = Product::where('Name', 'like', '%' . $search . '%')
                ->orWhere('ProductID', 'like', '%' . $search . '%')
                ->orWhere('Unit', 'like', '%' . $search . '%')
                ->orWhere('Unit_price', 'like', '%' . $search . '%')
                ->orWhere('Vat', 'like', '%' . $search . '%')
                ->paginate(10);
        } else {
            $products = Product::paginate(10);
        }

        return view('products.index', compact('products'));
    }
}
