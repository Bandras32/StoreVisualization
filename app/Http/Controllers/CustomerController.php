<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $customers = Customer::where('Name', 'like', '%' . $search . '%')
                ->orWhere('Location', 'like', '%' . $search . '%')
                ->orWhere('StoreID', 'like', '%' . $search . '%')
                ->paginate(10);
        } else {
            $customers = Customer::paginate(10);
        }

        return view('customers.index', compact('customers'));
    }
}
