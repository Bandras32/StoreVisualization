@extends('layouts.layout')

@section('title', 'Products')

@section('header')
    Product List
@endsection

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Product Records</h3>

            <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Search Product..." value="{{ request()->get('search') }}" class="px-4 py-2 border rounded-lg" />
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg">Search</button>
            </form>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VAT</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->ProductID }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->Name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->Unit_price, 0, ',', ' ') }} Ft</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->Unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->Vat }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $products->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection