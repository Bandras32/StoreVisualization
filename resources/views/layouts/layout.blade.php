<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Store Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4 text-center">
                <h1 class="text-2xl font-bold">Store Dashboard</h1>
            </div>
            <nav class="mt-6">
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            Dashboard
                        </a>
                    </li>
                        <li>
                            <a href="{{ route('customers.index') }}" class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('customers.*') ? 'bg-gray-700' : '' }}">
                                Customers
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('products.*') ? 'bg-gray-700' : '' }}">
                                Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('stores.index') }}" class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('stores.*') ? 'bg-gray-700' : '' }}">
                                Stores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}" class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('orders.*') ? 'bg-gray-700' : '' }}">
                                Orders
                            </a>
                        </li>
                    @if (Auth::user()->role === 'admin') {{-- Show only for admin --}}
                        <li>
                            <a href="{{ route('admin.users') }}" class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('admin.users') ? 'bg-gray-700' : '' }}">
                                User Management
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        @yield('header', 'Dashboard')
                    </h2>
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button 
                            id="profileButton" 
                            class="flex items-center space-x-2 text-gray-800 hover:text-gray-600 focus:outline-none"
                        >
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url ?? 'https://via.placeholder.com/50' }}" alt="{{ Auth::user()->name }}">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path d="M5.25 7.5L10 12.25L14.75 7.5H5.25Z"/>
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div 
                            id="profileDropdown" 
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10"
                        >
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Edit Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Section -->
            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        // JavaScript for Dropdown Toggle
        document.addEventListener('DOMContentLoaded', () => {
            const profileButton = document.getElementById('profileButton');
            const profileDropdown = document.getElementById('profileDropdown');

            // Toggle dropdown visibility
            profileButton.addEventListener('click', (event) => {
                event.stopPropagation(); // Prevent closing when clicking inside the dropdown
                profileDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                if (!profileDropdown.contains(event.target) && !profileButton.contains(event.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        });
    </script>

    @yield('scripts') 
</body>
</html>
