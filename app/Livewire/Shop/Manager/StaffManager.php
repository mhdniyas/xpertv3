<?php

namespace App\Livewire\Shop\Manager;

use App\Models\User;
use App\Models\Shop;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class StaffManager extends Component
{
    use WithPagination;

    // Shop and user properties
    public $shopId;
    public $shop;
    public $currentUser;

    // Staff management
    public $selectedUserId;
    public $selectedRole = 'staff';
    public $staffMembers = [];
    public $users = [];
    public $roles = ['staff', 'manager'];
    public $staffToRemove = null;
    public $searchTerm = '';

    // Modal controls
    public $showConfirmModal = false;

    protected $listeners = [
        'refreshStaffList' => 'loadStaffMembers'
    ];

    public function mount($shopId)
    {
        $this->shopId = $shopId;
        $this->currentUser = Auth::user();
        $this->loadShop();
        $this->loadStaffMembers();
        $this->loadAvailableUsers();
    }

    public function render()
    {
        return view('livewire.shop.manager.staff-manager', [
            'staffMembers' => $this->staffMembers,
            'users' => $this->users,
            'roles' => $this->roles,
            'shop' => $this->shop
        ]);
    }

    /**
     * Load the shop data
     */
    public function loadShop()
    {
        $this->shop = Shop::with('owner')->findOrFail($this->shopId);
    }

    /**
     * Load staff members for the selected shop
     */
    public function loadStaffMembers()
    {
        if (!$this->shopId) {
            return;
        }

        $this->shop = Shop::findOrFail($this->shopId);
        $this->staffMembers = $this->shop->staff()->with('role')->get();
    }

    /**
     * Load available users that can be assigned as staff
     */
    public function loadAvailableUsers()
    {
        $currentUser = Auth::user();

        // Get users based on search term
        $query = User::query();

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Get appropriate users based on role
        if ($currentUser->isSuperadmin() || $currentUser->isAdmin()) {
            // Admins can assign anyone
            $this->users = $query->with('role')->get();
        } else {
            // Regular users (shop owners) can only assign staff role users
            $staffRoleId = Role::where('name', 'staff')->value('id');
            $this->users = $query->where('role_id', $staffRoleId)->get();
        }
    }

    /**
     * Filter users when search term changes
     */
    public function updatedSearchTerm()
    {
        $this->loadAvailableUsers();
    }

    /**
     * Assign a staff member to the shop
     */
    public function assignStaff()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedRole' => 'required|in:manager,staff',
        ]);

        // Check authorization
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() && $this->shop->owner_id != $currentUser->id) {
            $this->addError('selectedUserId', 'You do not have permission to assign staff to this shop.');
            return;
        }

        // Check if user is already assigned to the shop
        $existing = $this->shop->staff()->where('user_id', $this->selectedUserId)->exists();

        if ($existing) {
            // Update their role instead
            $this->shop->staff()->updateExistingPivot($this->selectedUserId, [
                'role' => $this->selectedRole
            ]);
            session()->flash('message', 'Staff member role updated successfully.');
        } else {
            // Attach the user as new staff
            $this->shop->staff()->attach($this->selectedUserId, [
                'role' => $this->selectedRole
            ]);
            session()->flash('message', 'Staff member assigned to shop successfully.');
        }

        // Reset fields and refresh staff list
        $this->reset(['selectedUserId', 'selectedRole']);
        $this->loadStaffMembers();
    }

    /**
     * Confirm removing staff member from shop
     */
    public function confirmRemoveStaff($userId)
    {
        $this->staffToRemove = $userId;
        $this->showConfirmModal = true;
    }

    /**
     * Cancel staff removal
     */
    public function cancelRemoveStaff()
    {
        $this->staffToRemove = null;
        $this->showConfirmModal = false;
    }

    /**
     * Remove a staff member from the shop
     */
    public function removeStaff()
    {
        if (!$this->shopId || !$this->staffToRemove) {
            return;
        }

        // Check authorization
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() && $this->shop->owner_id != $currentUser->id) {
            $this->addError('staffToRemove', 'You do not have permission to remove staff from this shop.');
            return;
        }

        // Detach the staff member
        $this->shop->staff()->detach($this->staffToRemove);

        $this->staffToRemove = null;
        $this->showConfirmModal = false;
        session()->flash('message', 'Staff member removed successfully.');

        // Refresh staff list
        $this->loadStaffMembers();
    }
}
