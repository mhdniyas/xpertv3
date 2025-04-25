<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Dashboard extends Component
{
    use WithPagination;

    public $activeTab = 'overview';
    public $name;
    public $email;
    public $password;
    public $role_id;
    public $editingUserId = null;
    public $confirmingDelete = null;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|max:255',
        'role_id' => 'required|exists:roles,id',
    ];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        // Get the currently authenticated user with role relationship
        $currentUser = Auth::user();

        // Only show statistics to superadmins
        $totalUsers = $currentUser->isSuperadmin() ? User::count() : 0;
        $totalRoles = $currentUser->isSuperadmin() ? Role::count() : 0;
        $usersByRole = $currentUser->isSuperadmin() ? Role::withCount('users')->get() : collect();
        
        // Filter users based on role permissions
        if ($this->activeTab === 'users') {
            if ($currentUser->isSuperadmin()) {
                // Superadmin sees all users
                $users = User::with('role')->paginate(10);
            } else if ($currentUser->isAdmin()) {
                // Admin users only see users they created
                $users = User::with('role')
                    ->where('created_by', $currentUser->id)
                    ->paginate(10);
            } else {
                // Other roles don't see users
                $users = collect();
            }
        } else {
            $users = null;
        }
        
        // Get the latest 5 users for the overview tab - only for superadmins
        $recentUsers = $currentUser->isSuperadmin() 
            ? User::latest()->with('role')->take(5)->get() 
            : ($currentUser->isAdmin() 
                ? User::latest()->with('role')->where('created_by', $currentUser->id)->take(5)->get()
                : collect());

        // Get filtered roles based on user's permissions
        $roles = $this->getAvailableRoles();

        return view('livewire.dashboard', [
            'currentUser' => $currentUser,
            'totalUsers' => $totalUsers,
            'totalRoles' => $totalRoles,
            'usersByRole' => $usersByRole,
            'recentUsers' => $recentUsers,
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function save()
    {
        $currentUser = Auth::user();
        
        // Check basic permissions
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin()) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate();
        
        // Get the role being assigned
        $assignedRole = Role::find($this->role_id);
        if (!$assignedRole) {
            session()->flash('error', 'Invalid role selected.');
            return;
        }

        // Check if the current user has permission to assign this role
        if ($currentUser->isAdmin() && in_array($assignedRole->name, ['superadmin', 'admin'])) {
            abort(403, 'You do not have permission to assign this role.');
        }

        if ($this->editingUserId) {
            // Update existing user
            $user = User::with('role')->findOrFail($this->editingUserId);
            
            // Check if admin is trying to edit a superadmin or another admin
            if ($currentUser->isAdmin() && ($user->isSuperadmin() || $user->isAdmin())) {
                abort(403, 'You do not have permission to edit this user.');
            }

            // Add unique email validation except for current user
            $this->validate([
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($this->editingUserId),
                ],
            ]);

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
            ];

            // Only update password if provided
            if (!empty($this->password)) {
                $this->validate(['password' => 'min:8']);
                $userData['password'] = Hash::make($this->password);
            }

            $user->update($userData);

            session()->flash('message', 'User successfully updated.');
        } else {
            // Create new user
            $this->validate([
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users,email',
            ]);

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
                'created_by' => $currentUser->id, // Track who created this user
            ]);

            session()->flash('message', 'User successfully created.');
        }

        $this->resetFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editingUserId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // Don't fill password for security
        $this->role_id = $user->role_id;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function delete()
    {
        $currentUser = Auth::user();
        $userToDelete = User::with('role')->findOrFail($this->confirmingDelete);
        
        // Prevent deleting yourself
        if ($userToDelete->id === $currentUser->id) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->confirmingDelete = null;
            return;
        }

        // Permission checks based on role hierarchy
        if ($currentUser->isSuperadmin()) {
            // Superadmin can delete anyone
            $userToDelete->delete();
            session()->flash('message', 'User deleted successfully.');
        } else if ($currentUser->isAdmin()) {
            // Admins can delete only managers and normal users
            if ($userToDelete->isSuperadmin() || $userToDelete->isAdmin()) {
                abort(403, 'You do not have permission to delete this user.');
            }
            
            $userToDelete->delete();
            session()->flash('message', 'User deleted successfully.');
        } else {
            // No other role can delete users
            abort(403, 'You do not have permission to delete users.');
        }
        
        $this->confirmingDelete = null;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
    }

    public function resetFields()
    {
        $this->reset(['name', 'email', 'password', 'editingUserId']);
        $this->role_id = 4; // Reset to default role
    }

    public function cancel()
    {
        $this->resetFields();
    }

    /**
     * Get roles available for assignment based on current user's role
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAvailableRoles()
    {
        $currentUser = Auth::user();

        if ($currentUser->isSuperadmin()) {
            // Superadmins can assign any role
            return Role::all();
        } else if ($currentUser->isAdmin()) {
            // Admins can only assign manager and normal user roles
            return Role::whereIn('name', ['manager', 'normal user'])->get();
        } else {
            // Default case - only normal user role
            return Role::where('name', 'normal user')->get();
        }
    }
}
