@extends('layouts.layout')

@section('title', 'Orders')

@section('header')
    Order List
@endsection

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Order Records</h3>

            <form method="GET" action="{{ route('orders.index') }}" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Search Orders..." value="{{ request()->get('search') }}" class="px-4 py-2 border rounded-lg" />
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg">Search</button>
            </form>
        </div>

        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer->Name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->product->Name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->store->Name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->qty }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $orders->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
