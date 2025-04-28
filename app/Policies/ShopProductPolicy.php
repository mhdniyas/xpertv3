<?php

namespace App\Policies;

use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShopProductPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Admin and superadmin can do everything
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            return true;
        }

        return null; // Fall through to other policy methods
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view products
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ShopProduct $shopProduct): bool
    {
        // All authenticated users can view products
        return true;
    }

    /**
     * Determine whether the user can manage products for a shop.
     */
    public function manageProducts(User $user, $shop): bool
    {
        // Shop owner can manage products
        if ($shop->owner_id == $user->id) {
            return true;
        }

        // Shop manager (staff with 'manager' role) can manage products
        $staffRole = $shop->staff()
            ->where('user_id', $user->id)
            ->first()?->pivot?->role;

        return $staffRole === 'manager';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // This is handled through the "manageProducts" method instead
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShopProduct $shopProduct): bool
    {
        $shop = $shopProduct->shop;
        
        // Shop owner can update products
        if ($shop->owner_id == $user->id) {
            return true;
        }
        
        // Staff with manager role can update products
        $staffRole = $shop->staff()
            ->where('user_id', $user->id)
            ->first()?->pivot?->role;
            
        return $staffRole === 'manager';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShopProduct $shopProduct): bool
    {
        // Same rules as update - only owner and managers can delete
        return $this->update($user, $shopProduct);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ShopProduct $shopProduct): bool
    {
        // Same rules as update - only owner and managers can restore
        return $this->update($user, $shopProduct);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ShopProduct $shopProduct): bool
    {
        // Same rules as update - only owner and managers can force delete
        return $this->update($user, $shopProduct);
    }
}
