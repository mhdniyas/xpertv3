<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\ActivityLog;
use App\Models\ShopCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
    public $showHeaderNav = false; // Ensures navigation is removed
    public $showSignoutConfirm = false; // Property to control sign-out confirmation dialog
    public $photo;
    public $removePhoto = false; // Flag to remove the current photo
    public $oldPhoto;
    public $searchTerm = '';
    
    // Shop detail properties
    public $selectedShopId = null;
    public $shopDetailView = 'overview'; // overview, inventory, categories, sales
    public $productsByCategory = [];
    public $shopCategories = [];
    public $selectedCategoryId = null;

    public $currentUser;
    public $totalUsers;
    public $totalRoles;
    public $totalCategories;
    public $totalProducts;
    public $pendingShops;
    public $productSuggestions;
    public $userShopCount;
    public $userActiveShops;
    public $userShopProducts;
    public $shopSales;
    public $shopVisits;
    public $recentShopActivities;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|max:255',
        'role_id' => 'required|exists:roles,id',
    ];

    public function mount()
    {
        $this->currentUser = Auth::user();
        $this->loadDashboardData();
    }

    public function setActiveTab($tab)
    {
        if ($tab === 'signout') {
            $this->showSignoutConfirm = true;
        } else {
            $this->activeTab = $tab;
            $this->resetPage();
        }
    }

    public function loadDashboardData()
    {
        // Load system statistics
        if ($this->currentUser->isAdmin() || $this->currentUser->isSuperadmin()) {
            $this->totalUsers = User::count();
            $this->totalRoles = Role::count();
            $this->totalCategories = Category::count();
            $this->totalProducts = Product::count();
            $this->pendingShops = Shop::where('status', 'pending')->count();
            $this->productSuggestions = 0; // Replace with actual implementation when available
        }

        // Load user shop metrics
        $this->userShopCount = Shop::where('owner_id', $this->currentUser->id)->count();
        $this->userActiveShops = Shop::where('owner_id', $this->currentUser->id)
            ->where('status', 'active')
            ->count();
        $this->userShopProducts = ShopProduct::whereHas('shop', function ($query) {
            $query->where('owner_id', $this->currentUser->id);
        })->count();

        // Sample sales data (replace with actual implementation when available)
        $this->shopSales = [
            'total' => 5280,
            'growth' => 12,
            'period' => 'Last 30 days'
        ];

        // Sample visits data (replace with actual implementation when available)
        $this->shopVisits = 1240;

        // Recent shop activities for admins
        if ($this->currentUser->isAdmin() || $this->currentUser->isSuperadmin()) {
            $this->recentShopActivities = Shop::with('owner')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }
    }

    public function render()
    {
        // Get the currently authenticated user with role relationship
        $currentUser = Auth::user();

        // Initialize collections
        $userActivity = collect();
        $users = collect();
        $recentUsers = collect();
        $totalUsers = 0;
        $totalRoles = 0;
        $usersByRole = collect();
        $myTasks = collect();
        $myReports = collect();

        // Initialize admin dashboard stats
        $totalCategories = 0;
        $totalProducts = 0;
        $pendingShops = 0;
        $productSuggestions = 0;
        
        // Initialize shop metrics
        $userShopCount = 0;
        $userShopProducts = 0;
        $userActiveShops = 0;
        $topSellingProducts = collect();
        $recentShopActivities = collect();
        $shopSales = [];
        $shopVisits = 0;
        
        // Initialize shop details data
        $userShops = collect();
        $selectedShop = null;

        // Build user-specific data based on the active tab
        switch ($this->activeTab) {
            case 'overview':
                // Load different overview data based on user role
                if ($currentUser->isSuperadmin()) {
                    $totalUsers = User::count();
                    $totalRoles = Role::count();
                    $usersByRole = Role::withCount('users')->get();
                    $recentUsers = User::latest()->with('role')->take(5)->get();

                    // Admin dashboard stats
                    $totalCategories = Category::count();
                    $totalProducts = Product::where('is_global', true)->count();
                    $pendingShops = Shop::where('status', 'pending')->count();
                    $productSuggestions = Product::where('global_suggestion', true)->count();
                    
                    // Get overall shop metrics for superadmin
                    $userShopCount = Shop::count();
                    $userActiveShops = Shop::where('status', 'active')->count();
                    $userShopProducts = ShopProduct::count();
                    
                    // Get recent shop activities
                    $recentShopActivities = Shop::with('owner')
                        ->latest()
                        ->take(5)
                        ->get();
                        
                    // Calculate shop metrics for the last 30 days (mockup data)
                    // In a real application, this would be connected to your sales/analytics data
                    $shopSales = [
                        'total' => Shop::count() * rand(1000, 5000),
                        'growth' => rand(5, 25),
                        'period' => '30 days'
                    ];
                    
                    $shopVisits = Shop::count() * rand(500, 2000);
                    
                } elseif ($currentUser->isAdmin()) {
                    // Admin only sees users they created
                    $totalUsers = User::where('created_by', $currentUser->id)->count();
                    $usersByRole = Role::whereIn('name', ['manager', 'normal user'])
                        ->withCount(['users' => function($query) use ($currentUser) {
                            $query->where('created_by', $currentUser->id);
                        }])
                        ->get();
                    $recentUsers = User::latest()
                        ->with('role')
                        ->where('created_by', $currentUser->id)
                        ->take(5)
                        ->get();

                    // Admin dashboard stats
                    $totalCategories = Category::count();
                    $totalProducts = Product::where('is_global', true)->count();
                    $pendingShops = Shop::where('status', 'pending')->count();
                    $productSuggestions = Product::where('global_suggestion', true)->count();
                    
                    // Get shop metrics for admin view
                    $userShopCount = Shop::count();
                    $userActiveShops = Shop::where('status', 'active')->count();
                    $userShopProducts = ShopProduct::count();
                    
                    // Get recent shop activities
                    $recentShopActivities = Shop::with('owner')
                        ->latest()
                        ->take(5)
                        ->get();
                    
                    // Calculate shop metrics for the last 30 days (mockup data)
                    $shopSales = [
                        'total' => Shop::count() * rand(800, 4000),
                        'growth' => rand(5, 25),
                        'period' => '30 days'
                    ];
                    
                    $shopVisits = Shop::count() * rand(400, 1800);
                    
                } elseif ($currentUser->isManager()) {
                    // Manager sees only relevant information
                    $userActivity = User::where('role_id', 4) // normal users
                        ->latest()
                        ->take(5)
                        ->get();
                    
                    // Get shop metrics for managers
                    $userShopCount = Shop::where('owner_id', $currentUser->id)->count();
                    $userShopProducts = ShopProduct::whereHas('shop', function($query) use ($currentUser) {
                        $query->where('owner_id', $currentUser->id);
                    })->count();
                    $userActiveShops = Shop::where('owner_id', $currentUser->id)
                        ->where('status', 'active')
                        ->count();
                    
                    // Get top selling products (mock data for now)
                    $topSellingProducts = ShopProduct::whereHas('shop', function($query) use ($currentUser) {
                        $query->where('owner_id', $currentUser->id);
                    })
                    ->take(5)
                    ->get();
                    
                    // Calculate shop metrics for manager's shops (mockup data)
                    $shopSales = [
                        'total' => $userShopCount * rand(500, 2000),
                        'growth' => rand(3, 20),
                        'period' => '30 days'
                    ];
                    
                    $shopVisits = $userShopCount * rand(200, 1000);
                    
                } else {
                    // Regular user just sees their own data
                    $userActivity = collect([$currentUser]);
                    
                    // Get shop metrics if the user has any shops
                    $userShopCount = Shop::where('owner_id', $currentUser->id)->count();
                    $userShopProducts = ShopProduct::whereHas('shop', function($query) use ($currentUser) {
                        $query->where('owner_id', $currentUser->id);
                    })->count();
                    $userActiveShops = Shop::where('owner_id', $currentUser->id)
                        ->where('status', 'active')
                        ->count();
                    
                    // Calculate shop metrics for user's shops (mockup data) 
                    if ($userShopCount > 0) {
                        $shopSales = [
                            'total' => $userShopCount * rand(200, 1000),
                            'growth' => rand(1, 15),
                            'period' => '30 days'
                        ];
                        
                        $shopVisits = $userShopCount * rand(100, 500);
                    }
                }
                break;

            case 'users':
                // Show users based on permissions
                if ($currentUser->isSuperadmin()) {
                    // Superadmin sees all users with search
                    $users = User::with('role')
                        ->when($this->searchTerm, function($query) {
                            $query->where('name', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                        })
                        ->paginate(10);
                } elseif ($currentUser->isAdmin()) {
                    // Admin only sees users they created with search
                    $users = User::with('role')
                        ->where('created_by', $currentUser->id)
                        ->when($this->searchTerm, function($query) {
                            $query->where(function($q) {
                                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                            });
                        })
                        ->paginate(10);
                } else {
                    // Other roles don't see users tab
                    $users = collect();
                }
                break;

            case 'my_tasks':
                // This would connect to a tasks model if you had one
                // For now just demonstrate tab functionality
                $myTasks = collect([
                    ['id' => 1, 'title' => 'Sample Task 1', 'status' => 'In Progress'],
                    ['id' => 2, 'title' => 'Sample Task 2', 'status' => 'Completed'],
                ]);
                break;

            case 'my_reports':
                // This would connect to a reports model if you had one
                $myReports = collect([
                    ['id' => 1, 'title' => 'Monthly Report', 'date' => now()->format('Y-m-d')],
                    ['id' => 2, 'title' => 'Weekly Summary', 'date' => now()->subDays(7)->format('Y-m-d')]
                ]);
                break;

            case 'settings':
                // Settings data is handled in the view
                break;

            case 'categories':
                // Categories management tab is handled by the Livewire component
                break;

            case 'products':
                // Products management tab is handled by the Livewire component
                break;

            case 'shops':
                // Shop approvals tab is handled by the Livewire component
                break;

            case 'shop_details':
                // Get user's shops for selection
                if ($currentUser->isSuperadmin() || $currentUser->isAdmin()) {
                    // Admins can see all shops
                    $userShops = Shop::with('owner')->get();
                } else {
                    // Regular users see only their shops
                    $userShops = Shop::where('owner_id', $currentUser->id)->get();
                }
                
                // If a shop is selected, get its details
                if ($this->selectedShopId) {
                    $selectedShop = Shop::with('owner')->findOrFail($this->selectedShopId);
                    
                    // Load additional details based on the selected view
                    if ($this->shopDetailView === 'inventory' && empty($this->productsByCategory)) {
                        $this->loadShopInventory();
                    } elseif ($this->shopDetailView === 'categories' && empty($this->shopCategories)) {
                        $this->loadShopCategories();
                    }
                }
                break;

            case 'product_suggestions':
                // Product suggestions tab is handled by the Livewire component
                break;

            case 'activity_logs':
                // Activity logs tab is handled by the Livewire component (superadmin only)
                break;
        }

        // Get filtered roles based on user's permissions
        $roles = $this->getAvailableRoles();

        // Build navigation tabs based on user role
        $tabs = [
            'overview' => [
                'name' => 'Overview',
                'icon' => 'home'
            ]
        ];

        // Add user management for admins and superadmins
        if ($currentUser->isAdmin() || $currentUser->isSuperadmin()) {
            $tabs['users'] = [
                'name' => 'Users',
                'icon' => 'users'
            ];

            // Add admin dashboard tabs
            $tabs['categories'] = [
                'name' => 'Categories',
                'icon' => 'folder'
            ];

            $tabs['products'] = [
                'name' => 'Products',
                'icon' => 'shopping-bag'
            ];

            $tabs['shops'] = [
                'name' => 'Shop Approvals',
                'icon' => 'store'
            ];

            $tabs['product_suggestions'] = [
                'name' => 'Product Suggestions',
                'icon' => 'clipboard-check'
            ];
        }

        // Add activity logs tab for superadmins only
        if ($currentUser->isSuperadmin()) {
            $tabs['activity_logs'] = [
                'name' => 'Activity Logs',
                'icon' => 'clipboard-list'
            ];
        }

        // Add role-specific tabs
        if ($currentUser->isManager() || $currentUser->role->name == 'normal user') {
            $tabs['my_tasks'] = [
                'name' => 'My Tasks',
                'icon' => 'clipboard'
            ];

            $tabs['my_reports'] = [
                'name' => 'My Reports',
                'icon' => 'chart-bar'
            ];
        }

        // Add settings tab for all users
        $tabs['settings'] = [
            'name' => 'Settings',
            'icon' => 'cog'
        ];

        // Add sign-out tab for all users
        $tabs['signout'] = [
            'name' => 'Sign Out',
            'icon' => 'logout'
        ];

        // Add shop details tab for shop owners and admins
        if ($currentUser->isManager() || $currentUser->isAdmin() || $currentUser->isSuperadmin()) {
            $tabs['shop_details'] = [
                'name' => 'Shop Details',
                'icon' => 'store-alt'
            ];
        }

        return view('livewire.dashboard', [
            'currentUser' => $currentUser,
            'totalUsers' => $totalUsers,
            'totalRoles' => $totalRoles,
            'usersByRole' => $usersByRole,
            'recentUsers' => $recentUsers,
            'userActivity' => $userActivity,
            'users' => $users,
            'roles' => $roles,
            'showHeaderNav' => $this->showHeaderNav,
            'tabs' => $tabs,
            'myTasks' => $myTasks,
            'myReports' => $myReports,
            // Admin dashboard stats
            'totalCategories' => $totalCategories,
            'totalProducts' => $totalProducts,
            'pendingShops' => $pendingShops,
            'productSuggestions' => $productSuggestions,
            // Shop metrics
            'userShopCount' => $userShopCount,
            'userShopProducts' => $userShopProducts,
            'userActiveShops' => $userActiveShops,
            'topSellingProducts' => $topSellingProducts,
            'recentShopActivities' => $recentShopActivities,
            'shopSales' => $shopSales,
            'shopVisits' => $shopVisits,
            // Shop details data
            'userShops' => $userShops,
            'selectedShop' => $selectedShop,
            'shopDetailView' => $this->shopDetailView,
            'productsByCategory' => $this->productsByCategory,
            'shopCategories' => $this->shopCategories,
            'selectedCategoryId' => $this->selectedCategoryId
        ]);
    }

    // Updated search method to filter users
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // ... rest of the class remains the same (save, edit, delete, etc.)

    public function save()
    {
        $currentUser = Auth::user();

        // Check basic permissions
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin()) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate();

        // Add photo validation if provided
        if ($this->photo) {
            $this->validate([
                'photo' => 'image|max:1024', // 1MB Max
            ]);
        }

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

            // Handle photo if provided
            if ($this->photo) {
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                // Store new photo
                $path = $this->photo->store('photos', 'public');
                $userData['photo'] = $path;
            } elseif ($this->removePhoto) {
                // Remove photo if requested
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $userData['photo'] = null;
            }

            $user->update($userData);
            session()->flash('message', 'User successfully updated.');
        } else {
            // Create new user
            $this->validate([
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users,email',
            ]);

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
                'created_by' => $currentUser->id, // Track who created this user
                'photo' => $this->photo ? $this->photo->store('photos', 'public') : null,
            ];

            User::create($userData);

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
        $this->photo = null;
        $this->removePhoto = false;
        $this->oldPhoto = $user->photo; // Store the current photo path
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
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

    public function resetFields()
    {
        $this->reset(['name', 'email', 'password', 'editingUserId', 'photo', 'removePhoto']);
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

    /**
     * Perform sign-out action
     */
    public function signOut()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Cancel sign-out confirmation
     */
    public function cancelSignout()
    {
        $this->showSignoutConfirm = false;
    }

    /**
     * Save user photo
     */
    public function savePhoto()
    {
        $this->validate([
            'photo' => 'required|image|max:1024', // 1MB Max
        ]);

        $currentUser = Auth::user();

        // Delete old photo if exists
        if ($currentUser->photo && Storage::disk('public')->exists($currentUser->photo)) {
            Storage::disk('public')->delete($currentUser->photo);
        }

        // Store and save new photo path
        $path = $this->photo->store('photos', 'public');
        $currentUser->photo = $path;
        $currentUser->save();

        $this->photo = null; // Reset the upload field
        session()->flash('message', 'Photo updated successfully');
    }

    /**
     * Remove the user's photo
     */
    public function deletePhoto()
    {
        $currentUser = Auth::user();

        if ($currentUser->photo && Storage::disk('public')->exists($currentUser->photo)) {
            Storage::disk('public')->delete($currentUser->photo);
        }

        $currentUser->photo = null;
        $currentUser->save();

        session()->flash('message', 'Photo removed successfully');
    }

    /**
     * Load user photo when editing
     */
    public function loadUserPhoto($userId)
    {
        $user = User::findOrFail($userId);
        $this->oldPhoto = $user->photo;
    }

    /**
     * Set the selected shop and load its details
     */
    public function selectShop($shopId)
    {
        $this->selectedShopId = $shopId;
        $this->shopDetailView = 'overview';
        $this->productsByCategory = [];
        $this->shopCategories = [];
        $this->selectedCategoryId = null;
    }
    
    /**
     * Set the shop details view (summary, inventory, categories)
     */
    public function setShopDetailView($view)
    {
        $this->shopDetailView = $view;
        
        if ($view === 'inventory' && empty($this->productsByCategory)) {
            $this->loadShopInventory();
        } elseif ($view === 'categories' && empty($this->shopCategories)) {
            $this->loadShopCategories();
        }
    }
    
    /**
     * Load details for the selected shop
     */
    public function loadShopDetails()
    {
        if (!$this->selectedShopId) {
            return;
        }
        
        // Load basic shop information
        $shop = Shop::with('owner')->findOrFail($this->selectedShopId);
        
        // Load summary data
        $this->shopProducts = ShopProduct::where('shop_id', $this->selectedShopId)
            ->count();
        $this->shopCategories = ShopCategory::where('shop_id', $this->selectedShopId)
            ->count();
        
        // Load sample shop statistics (replace with actual implementation)
        $this->shopSales = [
            'total' => rand(500, 5000),
            'growth' => rand(5, 25),
            'period' => 'Last 30 days'
        ];
        
        $this->shopVisits = rand(100, 1000);
    }
    
    /**
     * Load shop inventory with category grouping
     */
    public function loadShopInventory()
    {
        if (!$this->selectedShopId) {
            return;
        }
        
        // Get all categories that have products in this shop
        $shopCategories = ShopProduct::where('shop_id', $this->selectedShopId)
            ->join('products', 'shop_products.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name')
            ->distinct()
            ->orderBy('categories.name')
            ->get();
            
        // Group products by category
        $this->productsByCategory = [];
        
        foreach ($shopCategories as $category) {
            $products = ShopProduct::where('shop_id', $this->selectedShopId)
                ->join('products', 'shop_products.product_id', '=', 'products.id')
                ->where('products.category_id', $category->id)
                ->select(
                    'shop_products.id',
                    'shop_products.product_id',
                    'products.name',
                    'products.description',
                    'shop_products.price',
                    'shop_products.stock',
                    'shop_products.status'
                )
                ->orderBy('products.name')
                ->get();
                
            $this->productsByCategory[$category->id] = [
                'category_name' => $category->name,
                'products' => $products
            ];
        }
        
        if (!empty($this->productsByCategory)) {
            // Set the first category as selected by default
            $firstCategory = array_key_first($this->productsByCategory);
            $this->selectedCategoryId = $firstCategory;
        }
    }
    
    /**
     * Load shop categories with product counts
     */
    public function loadShopCategories()
    {
        if (!$this->selectedShopId) {
            return;
        }
        
        // Get all shop categories
        $shopCategories = ShopCategory::where('shop_id', $this->selectedShopId)
            ->orderBy('parent_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
            
        // Create hierarchical structure
        $categoryTree = [];
        $categoriesById = [];
        
        // First pass: index all categories by ID
        foreach ($shopCategories as $category) {
            $categoriesById[$category->id] = [
                'id' => $category->id,
                'name' => $category->name,
                'parent_id' => $category->parent_id,
                'description' => $category->description,
                'status' => $category->status,
                'children' => [],
                'product_count' => 0
            ];
        }
        
        // Second pass: count products in each category
        foreach ($categoriesById as $id => $categoryData) {
            // Count products directly in this category
            $productCount = ShopProduct::where('shop_id', $this->selectedShopId)
                ->join('products', 'shop_products.product_id', '=', 'products.id')
                ->where('products.category_id', $id)
                ->count();
                
            $categoriesById[$id]['product_count'] = $productCount;
        }
        
        // Third pass: build the tree structure
        foreach ($categoriesById as $id => $categoryData) {
            if (!$categoryData['parent_id']) {
                // This is a root category
                $categoryTree[$id] = &$categoriesById[$id];
            } else {
                // This is a child category
                if (isset($categoriesById[$categoryData['parent_id']])) {
                    $categoriesById[$categoryData['parent_id']]['children'][$id] = &$categoriesById[$id];
                }
            }
        }
        
        $this->shopCategories = $categoryTree;
        
        if (!empty($this->shopCategories)) {
            // Set the first category as selected by default
            $firstCategory = array_key_first($this->shopCategories);
            $this->selectedCategoryId = $firstCategory;
        }
    }
    
    /**
     * Select a category to view its products
     */
    public function selectCategory($categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->loadCategoryProducts($categoryId);
    }
    
    /**
     * Load products for a specific category
     */
    public function loadCategoryProducts($categoryId)
    {
        if (!$this->selectedShopId || !$categoryId) {
            return;
        }
        
        $this->categoryProducts = ShopProduct::where('shop_id', $this->selectedShopId)
            ->join('products', 'shop_products.product_id', '=', 'products.id')
            ->where('products.category_id', $categoryId)
            ->select(
                'shop_products.id',
                'shop_products.product_id',
                'products.name',
                'products.description',
                'shop_products.price',
                'shop_products.stock',
                'shop_products.status'
            )
            ->orderBy('products.name')
            ->get();
    }
}
