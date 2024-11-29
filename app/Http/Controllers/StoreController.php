<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $stores = Store::where('Name', 'like', '%' . $search . '%')
                ->orWhere('Location', 'like', '%' . $search . '%')
                ->orWhere('StoreID', 'like', '%' . $search . '%')
                ->paginate(10);
        } else {
            $stores = Store::paginate(10);
        }

        return view('stores.index', compact('stores'));
    }
}
