<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserManager;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

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

require __DIR__.'/auth.php';
