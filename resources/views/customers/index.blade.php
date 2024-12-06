@extends('layouts.layout')

@section('title', 'Customers')

@section('header')
    Vásárló lista
@endsection

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Vásárlói adatok</h3>

            <form method="GET" action="{{ route('customers.index') }}" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Vásárló keresés..." value="{{ request()->get('search') }}" class="px-4 py-2 border rounded-lg" />
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg">Keresés</button>
            </form>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Név</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Város</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->CustomerID }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->Name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->City }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->Email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $customers->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
