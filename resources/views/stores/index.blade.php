@extends('layouts.layout')

@section('title', 'Stores')

@section('header')
    Store List
@endsection

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Store Records</h3>
            <!-- Search Form -->
            <form method="GET" action="{{ route('stores.index') }}" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Search Stores..." value="{{ request()->get('search') }}" class="px-4 py-2 border rounded-lg" />
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg">Search</button>
            </form>
        </div>
        
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($stores as $store)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $store->StoreID }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $store->Name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $store->Location }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
         <!-- Pagination -->
         <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $stores->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
