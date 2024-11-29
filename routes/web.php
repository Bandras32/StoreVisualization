<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

// Welcome page route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route (uses your DashboardController@index for handling logic)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Ensure this is inside the `auth` middleware and `can:admin` gate
Route::get('/admin/users', [AdminController::class, 'showUsers'])->middleware('auth')->name('admin.users');

Route::patch('/admin/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('admin.updateRole');


// Breeze profile management routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Your old resource routes with auth middleware protection
Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('orders', OrderController::class);

    // Example route for testing data
    Route::get('/test-data', function () {
        return App\Models\Customer::all();
    });
});

// Include Breeze auth routes
require __DIR__ . '/auth.php';
