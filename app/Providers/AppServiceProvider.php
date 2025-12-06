<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::if('haspermission', function ($permission) {
            return Auth::check() && Auth::user()->can($permission);
        });

        Blade::if('hasanypermission', function (...$permissions) {
            return Auth::check() && Auth::user()->hasAnyPermission($permissions);
        });

        Blade::if('hasallpermissions', function (...$permissions) {
            return Auth::check() && Auth::user()->hasAllPermissions($permissions);
        });
    }
}
