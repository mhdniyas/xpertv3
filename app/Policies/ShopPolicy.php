<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shop $shop): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only managers, admins, and superadmins can create shops
        return $user->isManager() || $user->isAdmin() || $user->isSuperadmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shop $shop): bool
    {
        // Superadmin can update any shop
        if ($user->isSuperadmin()) {
            return true;
        }
        
        // Admin can update any shop
        if ($user->isAdmin()) {
            return true;
        }
        
        // Shop owner can update their own shop
        if ($shop->owner_id === $user->id) {
            return true;
        }
        
        // Shop staff with manager role can update the shop
        return $shop->staff()->where('user_id', $user->id)
            ->where('role', 'manager')
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shop $shop): bool
    {
        // Only superadmins and admins can delete shops
        if ($user->isSuperadmin() || $user->isAdmin()) {
            return true;
        }
        
        // Shop owner can delete their own shop
        return $shop->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shop $shop): bool
    {
        return $user->isSuperadmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shop $shop): bool
    {
        return $user->isSuperadmin();
    }
}