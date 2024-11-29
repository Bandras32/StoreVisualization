<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{


    public function boot()
    {
        $this->registerPolicies();
    
        Gate::define('admin', function ($user) {
            \Log::info('Checking admin access', ['role' => $user->role]);
            return $user->role === 'admin'; 
        });
    }
}