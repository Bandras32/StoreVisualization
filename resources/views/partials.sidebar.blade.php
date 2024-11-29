<aside class="w-64 h-screen bg-gray-800 text-white">
    <div class="p-4 font-bold">Store Dashboard</div>
    <nav>
        <ul>
            <li><a href="{{ route('dashboard') }}" class="block p-2 hover:bg-gray-700">Dashboard</a></li>
            <li><a href="{{ route('customers.index') }}" class="block p-2 hover:bg-gray-700">Customers</a></li>
            <li><a href="{{ route('products.index') }}" class="block p-2 hover:bg-gray-700">Products</a></li>
            <li><a href="{{ route('stores.index') }}" class="block p-2 hover:bg-gray-700">Stores</a></li>
            <li><a href="{{ route('orders.index') }}" class="block p-2 hover:bg-gray-700">Orders</a></li>
        </ul>
    </nav>
</aside>
