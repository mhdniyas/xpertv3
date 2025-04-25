<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Define role-based gates
        Gate::define('isSuperadmin', function (User $user) {
            return $user->isSuperadmin();
        });

        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin() || $user->isSuperadmin();
        });

        Gate::define('isManager', function (User $user) {
            return $user->isManager() || $user->isAdmin() || $user->isSuperadmin();
        });
    }
}
