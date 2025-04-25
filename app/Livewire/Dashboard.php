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
        
        // Get total count of users and roles
        $totalUsers = User::count();
        $totalRoles = Role::count();
        
        // Get counts of users by role
        $usersByRole = Role::withCount('users')->get();
        
        // Get the latest 5 users for the overview tab
        $recentUsers = User::latest()->with('role')->take(5)->get();
        
        // For the users tab, get paginated users
        $users = $this->activeTab === 'users' ? User::with('role')->paginate(10) : null;
        
        // Get all roles for the user form
        $roles = Role::all();
        
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
        // Check permissions - manually checking role names for safety
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->role || ($currentUser->role->name !== 'admin' && $currentUser->role->name !== 'superadmin')) {
            abort(403);
        }

        $this->validate();

        if ($this->editingUserId) {
            // Update existing user
            $user = User::findOrFail($this->editingUserId);

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
        // Only superadmin can delete users - manually checking role name for safety
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->role || $currentUser->role->name !== 'superadmin') {
            abort(403);
        }

        $user = User::findOrFail($this->confirmingDelete);

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->confirmingDelete = null;
            return;
        }

        $user->delete();
        session()->flash('message', 'User deleted successfully.');
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
}
