@extends('layouts.layout')

@section('title', 'Felhasználók Kezelése')

@section('header')
    Adminisztrátori felület
@endsection

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Üzenetek -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="px-6 py-4 sm:px-8 flex justify-between items-center border-b border-gray-300">
            <h3 class="text-lg leading-6 font-semibold text-gray-900">Felhasználói adatok</h3>
            
            <!-- Kereső űrlap -->
            <form method="GET" action="{{ route('admin.users') }}" class="flex items-center space-x-4">
                <input type="text" name="search" placeholder="Felhasználó keresése..." value="{{ request()->get('search') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500" />
                <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg hover:bg-gray-700">Keresés</button>
            </form>
        </div>

        <table class="min-w-full divide-y divide-gray-300 mt-4">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Felhasználónév</th>
                    <th class="px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">E-mail</th>
                    <th class="px-10 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Szerepkör</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-10 py-4 whitespace-nowrap text-sm">
                            <!-- Szerepkör frissítése -->
                            <form method="POST" action="{{ route('admin.updateRole', $user) }}" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="border border-gray-300 px-4 pr-9 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Felhasználó</option>
                                </select>
                                <button type="submit" class="ml-3 bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600">
                                    Frissítés
                                </button>
                            </form>

                            <!-- Felhasználó törlése -->
                            <form method="POST" action="{{ route('admin.deleteUser', $user) }}" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg hover:bg-red-600" 
                                        onclick="return confirm('Biztosan törölni szeretné ezt a felhasználót?')">
                                    Törlés
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Lapozás -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-300 sm:px-8">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
