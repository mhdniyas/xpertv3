<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use App\Policies\ShopPolicy;
use App\Policies\ShopCategoryPolicy;
use App\Policies\ShopProductPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Shop::class => ShopPolicy::class,
        ShopCategory::class => ShopCategoryPolicy::class,
        ShopProduct::class => ShopProductPolicy::class,
    ];
    
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
        // Register policies
        $this->registerPolicies();
        
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
