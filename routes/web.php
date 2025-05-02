<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserManager;
use App\Livewire\Shop\ShopMarketplace;
use App\Livewire\Shop\ShopCategoryManager;
use App\Livewire\Shop\ShopProductManager;
use App\Livewire\Shop\ShopManager;
use App\Livewire\Shop\RentalBookingManager;
use App\Livewire\Shop\RentalProductManager;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RentalController;
use App\Livewire\Marketplace\MarketplaceLanding;
// Removed AdminDashboard import as it doesn't exist

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Settings routes
Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    Route::view('profile', 'profile')
        ->name('profile');
});

// Role-based dashboard routes
Route::middleware(['auth'])->group(function () {
    // Superadmin routes
    Route::view('superadmin/dashboard', 'admin.superadmin-dashboard')
        ->middleware('can:isSuperadmin')
        ->name('superadmin.dashboard');

    // Admin routes
    Route::view('admin/dashboard', 'admin.admin-dashboard')
        ->middleware('can:isAdmin')
        ->name('admin.dashboard');

    // Manager routes
    Route::view('manager/dashboard', 'admin.manager-dashboard')
        ->middleware('can:isManager')
        ->name('manager.dashboard');

    // Normal user routes
    Route::view('user/dashboard', 'user-dashboard')
        ->name('user.dashboard');

    // User Management
    Route::get('admin/users', UserManager::class)
        ->middleware('can:isAdmin')
        ->name('admin.users');
});

// Shop Marketplace Routes
Route::get('/marketplace', [ShopController::class, 'index'])->name('marketplace');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shop/{shopSlug}/product/{productSlug?}', [ShopController::class, 'showProduct'])->name('shop.product.show');

// New Marketplace Landing Page
Route::get('/marketplace/landing', MarketplaceLanding::class)->name('marketplace.landing');

// Shop Management Routes (Protected)
Route::middleware(['auth'])->group(function () {
    // Shop Management
    Route::get('/shops', ShopManager::class)
        ->name('shops.manage');

    // Shop Details
    Route::get('/shop/{shopId}/details', App\Livewire\Shop\ShopDetails::class)
        ->name('shop.details');

    // Shop Manager Dashboard
    Route::get('/shop/{shopId}/manager', App\Livewire\Shop\ShopDetails::class)
        ->defaults('view', 'manager')
        ->name('shop.manager');

    // Shop Category Management
    Route::get('/shop/{shop}/categories', ShopCategoryManager::class)
        ->name('shop.categories')
        ->middleware('can:manageCategories,shop');

    // Shop Product Management
    Route::get('/shop/{shop}/products', ShopProductManager::class)
        ->name('shop.products')
        ->middleware('can:manageProducts,shop');
    
    // Rental Management Routes
    Route::prefix('rentals')->name('shops.rentals')->group(function () {
        Route::get('/', [RentalController::class, 'index']);
        Route::get('/{shopId}/dashboard', [RentalController::class, 'dashboard'])->name('.dashboard');
        
        // Rental Products Management - Using controller instead of missing Livewire component
        Route::get('/{shopId}/products', [RentalController::class, 'products'])
            ->name('.products')
            ->middleware('can:manageProducts,App\Models\Shop,shopId');
            
        // Rental Bookings Management - Also using controller instead of missing Livewire component
        Route::get('/{shopId}/bookings', [RentalController::class, 'bookings'])
            ->name('.bookings')
            ->middleware('can:manageProducts,App\Models\Shop,shopId');
            
        // Customer Management
        Route::get('/{shopId}/customers', [RentalController::class, 'customers'])
            ->name('.customers')
            ->middleware('can:manageProducts,App\Models\Shop,shopId');
    });
});

require __DIR__.'/auth.php';
