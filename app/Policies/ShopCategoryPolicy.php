<?php

namespace App\Policies;

use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShopCategoryPolicy
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
        // All authenticated users can view categories
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ShopCategory $shopCategory): bool
    {
        // All authenticated users can view categories
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only shop owners or managers can create categories
        // This is handled through the "manageCategories" method on the Shop model
        return false;
    }

    /**
     * Determine whether the user can manage categories for a shop.
     */
    public function manageCategories(User $user, $shop): bool
    {
        // Shop owner can manage categories
        if ($shop->owner_id == $user->id) {
            return true;
        }

        // Shop manager (staff with 'manager' role) can manage categories
        $staffRole = $shop->staff()
            ->where('user_id', $user->id)
            ->first()?->pivot?->role;

        return $staffRole === 'manager';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShopCategory $shopCategory): bool
    {
        // Shop owner and managers can update categories
        $shop = $shopCategory->shop;
        
        // Shop owner can update
        if ($shop->owner_id == $user->id) {
            return true;
        }
        
        // Staff with manager role can update
        $staffRole = $shop->staff()
            ->where('user_id', $user->id)
            ->first()?->pivot?->role;
            
        return $staffRole === 'manager';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShopCategory $shopCategory): bool
    {
        // Same rules as update - only owner and managers can delete
        return $this->update($user, $shopCategory);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ShopCategory $shopCategory): bool
    {
        // Same rules as update - only owner and managers can restore
        return $this->update($user, $shopCategory);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ShopCategory $shopCategory): bool
    {
        // Same rules as update - only owner and managers can force delete
        return $this->update($user, $shopCategory);
    }
}
