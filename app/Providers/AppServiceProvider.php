<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('Admin', function (User $user) {
            return ($user->role->name === 'Super Admin');
        });

        Blade::if('Admin', function () {
            return request()->user()?->can('Admin');
        });

        Gate::define('Accountant', function (User $user) {
            return ($user->role->name === 'Super Admin' || $user->role->name === 'Accountant');
        });

        Blade::if('Accountant', function () {
            return request()->user()?->can('Accountant');
        });
    }
}
