<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate untuk isAdmin (semua user dengan role admin atau id=1)
        Gate::define('isAdmin', function ($user) {
            return $user->id === 1 || ($user->role ?? null) === 'admin';
        });

        // Gate untuk isProcurement (admin atau procurement staff)
        Gate::define('isProcurement', function ($user) {
            return in_array($user->id, [1]) || ($user->role ?? null) === 'procurement';
        });
    }
}
