@extends('layouts.layout')

@section('title', 'Products')

@section('header')
    Termék lista
@endsection

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Termék adatok</h3>

            <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Keress Terméket..." value="{{ request()->get('search') }}" class="px-4 py-2 border rounded-lg" />
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg">Keresés</button>
            </form>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Termék neve</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Termék ára</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Egység</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ÁFA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forgalmazás</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->ProductID }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->Name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->Unit_price, 0, ',', ' ') }} Ft</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->Unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->Vat, 0, ',', ' ') }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->Profit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $product->IsActive ? 'Forgalmazott' : 'Nem forgalmazott' }}
                            </td>
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
