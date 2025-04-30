<?php

namespace App\Livewire\Shop;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\ShopCategory;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopDetails extends Component
{
    use WithPagination;

    // Current authenticated user
    public $currentUser;

    // Shop detail properties
    public $selectedShopId = null;
    public $shopDetailView = 'overview'; // overview, inventory, categories, sales, staff, manager
    public $productsByCategory = [];
    public $shopCategories = []; // Ensuring it's initialized as an empty array
    public $selectedCategoryId = null;
    public $showShopManager = false;
    public $searchTerm = '';
    public $categoriesCount = 0;
    public $categoryProducts = [];
    public $staffToRemove = null; // Added missing property for staff removal confirmation

    // Staff management properties
    public $selectedUserId;
    public $selectedRole = 'staff';
    public $shopStaff = [];
    public $availableUsers = [];

    // Product form properties
    public $productName;
    public $productDescription;
    public $productPrice;
    public $productStock = 0;
    public $productCategoryId;
    public $productStatus = 'active';
    public $productUnit = 'piece';

    // Shop statistics
    public $shopProducts;
    public $shopSales;
    public $shopVisits;

    protected $queryString = [
        'selectedShopId' => ['except' => null],
        'shopDetailView' => ['except' => 'overview'],
    ];

    protected $listeners = [
        'refreshShopData' => 'loadShopDetails'
    ];

    public function mount($shopId = null, $view = null)
    {
        $this->currentUser = Auth::user();

        if ($shopId) {
            $this->selectedShopId = $shopId;
            
            // Set the view if provided in the URL
            if ($view === 'manager') {
                $this->shopDetailView = 'manager';
                $this->showShopManager = true;
                $this->loadShopStaff();
                $this->loadAvailableUsers();
            }
            
            $this->loadShopDetails();
        }
    }

    public function render()
    {
        $currentUser = Auth::user();
        $userShops = collect();
        $selectedShop = null;

        // Get user's shops for selection
        if ($currentUser->isSuperadmin() || $currentUser->isAdmin()) {
            // Admins can see all shops
            $userShops = Shop::with('owner')->get();
        } else {
            // Regular users see only their shops
            $userShops = Shop::where('owner_id', $currentUser->id)
                ->orWhereHas('staff', function($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                })
                ->get();
        }

        // If a shop is selected, get its details
        if ($this->selectedShopId) {
            $selectedShop = Shop::with('owner')->findOrFail($this->selectedShopId);

            // Load additional details based on the selected view
            if ($this->shopDetailView === 'inventory' && empty($this->productsByCategory)) {
                $this->loadShopInventory();
            } elseif ($this->shopDetailView === 'categories' && empty($this->shopCategories)) {
                $this->loadShopCategories();
            } elseif ($this->shopDetailView === 'manager' && empty($this->shopStaff)) {
                $this->loadShopStaff();
                $this->loadAvailableUsers();
            }
        }

        return view('livewire.shop.shop-details', [
            'currentUser' => $currentUser,
            'userShops' => $userShops,
            'selectedShop' => $selectedShop,
            'shopDetailView' => $this->shopDetailView,
            'productsByCategory' => $this->productsByCategory,
            'shopCategories' => $this->shopCategories,
            'selectedCategoryId' => $this->selectedCategoryId,
            'shopStaff' => $this->shopStaff,
            'availableUsers' => $this->availableUsers
        ]);
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
        $this->loadShopDetails();
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

        // Changed from count() to returning an empty array
        // This ensures $shopCategories is always an array, not an integer
        if ($this->shopDetailView !== 'categories') {
            $categoriesCount = ShopCategory::where('shop_id', $this->selectedShopId)
                ->count();
            // Only store the count, don't override the actual categories array
            $this->categoriesCount = $categoriesCount;
        } else {
            // If we're in categories view, we'll load the full categories data
            $this->loadShopCategories();
        }

        // Load shop statistics (replace with actual implementation)
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

        // Get shop categories with products
        $shopCategories = ShopCategory::where('shop_id', $this->selectedShopId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Group products by category
        $this->productsByCategory = [];

        foreach ($shopCategories as $category) {
            // Get products in this category
            $products = ShopProduct::where('shop_products.shop_id', $this->selectedShopId)
                ->where('shop_products.shop_category_id', $category->id)
                ->select(
                    'shop_products.id',
                    'shop_products.name',
                    'shop_products.description', // Now including the description field
                    'shop_products.global_product_id',
                    'shop_products.price',
                    'shop_products.stock_quantity as stock',
                    'shop_products.status',
                    'shop_products.source_type'
                )
                ->orderBy('shop_products.name')
                ->get();

            // Only add category if it has products
            if ($products->count() > 0) {
                $this->productsByCategory[$category->id] = [
                    'category_name' => $category->name,
                    'products' => $products
                ];
            }
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
            // Count products directly in this category - using shop_category_id
            $productCount = ShopProduct::where('shop_products.shop_id', $this->selectedShopId)
                ->where('shop_products.shop_category_id', $id)
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

        $this->categoryProducts = ShopProduct::where('shop_products.shop_id', $this->selectedShopId)
            ->where('shop_products.shop_category_id', $categoryId)
            ->select(
                'shop_products.id',
                'shop_products.name',
                'shop_products.description', // Now including the description field
                'shop_products.global_product_id',
                'shop_products.price',
                'shop_products.stock_quantity as stock',
                'shop_products.status',
                'shop_products.source_type'
            )
            ->orderBy('shop_products.name')
            ->get();
    }

    /**
     * Toggle shop manager view
     */
    public function toggleShopManager($shopId = null)
    {
        if ($shopId) {
            $this->selectedShopId = $shopId;
        }

        $this->shopDetailView = 'manager';
        $this->showShopManager = true;
    }

    /**
     * Close shop manager view and return to overview
     */
    public function closeShopManager()
    {
        $this->showShopManager = false;
        $this->shopDetailView = 'overview';
    }

    /**
     * Save a new product to the shop
     */
    public function saveProduct()
    {
        // Validate the product form data
        $this->validate([
            'productName' => 'required|string|max:255',
            'productCategoryId' => 'required|exists:shop_categories,id',
            'productPrice' => 'required|numeric|min:0',
            'productDescription' => 'nullable|string|max:1000',
            'productStock' => 'required|integer|min:0',
            'productStatus' => 'required|in:active,inactive',
            'productUnit' => 'nullable|string|max:50',
        ]);

        // Check if user has permission to add products to this shop
        $shop = Shop::findOrFail($this->selectedShopId);
        $currentUser = Auth::user();
        $canManageProducts = $currentUser->isAdmin() ||
                            $currentUser->isSuperadmin() ||
                            $shop->owner_id == $currentUser->id ||
                            $shop->staff()->where('user_id', $currentUser->id)
                                 ->where('role', 'manager')
                                 ->exists();

        if (!$canManageProducts) {
            session()->flash('error', 'You do not have permission to add products to this shop.');
            return;
        }

        try {
            // Create the new shop product
            $product = new ShopProduct();
            $product->shop_id = $this->selectedShopId;
            $product->shop_category_id = $this->productCategoryId;
            $product->name = $this->productName;
            $product->description = $this->productDescription;
            $product->price = $this->productPrice;
            $product->stock_quantity = $this->productStock;
            $product->status = $this->productStatus;
            $product->unit = $this->productUnit;
            $product->source_type = 'local'; // This is a locally created product
            $product->created_by = $currentUser->id;
            $product->save();

            // Reset the form fields
            $this->reset([
                'productName',
                'productDescription',
                'productPrice',
                'productStock',
                'productUnit'
            ]);
            $this->productStatus = 'active';

            // Reload the inventory data
            $this->loadShopInventory();

            // Set success message
            session()->flash('message', 'Product added successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding product: ' . $e->getMessage());
        }
    }

    /**
     * Confirm removing a staff member
     */
    public function confirmRemoveStaff($staffId)
    {
        $this->staffToRemove = $staffId;
    }

    /**
     * Remove a staff member from the shop
     */
    public function removeStaff()
    {
        if (!$this->staffToRemove || !$this->selectedShopId) {
            return;
        }

        try {
            $shop = Shop::findOrFail($this->selectedShopId);

            // Check permission to remove staff
            $currentUser = Auth::user();
            if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() && $shop->owner_id != $currentUser->id) {
                session()->flash('error', 'You do not have permission to remove staff from this shop.');
                $this->staffToRemove = null;
                return;
            }

            // Remove the staff member
            $shop->staff()->detach($this->staffToRemove);
            session()->flash('message', 'Staff member removed successfully.');

            // Reset staff removal confirmation
            $this->staffToRemove = null;

            // Refresh staff list if needed
            // You could add code here to refresh the staff list if it's being used in the view
        } catch (\Exception $e) {
            session()->flash('error', 'Error removing staff member: ' . $e->getMessage());
        }
    }

    /**
     * Assign a staff member to the shop
     */
    public function assignStaff()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedRole' => 'required|in:staff,manager',
        ]);

        try {
            $shop = Shop::findOrFail($this->selectedShopId);

            // Check permission to add staff
            $currentUser = Auth::user();
            if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() && $shop->owner_id != $currentUser->id) {
                session()->flash('error', 'You do not have permission to add staff to this shop.');
                return;
            }

            // Check if user is already a staff member
            if ($shop->staff()->where('user_id', $this->selectedUserId)->exists()) {
                session()->flash('error', 'This user is already a staff member of this shop.');
                return;
            }

            // Assign the user as staff
            $shop->staff()->attach($this->selectedUserId, [
                'role' => $this->selectedRole,
                'added_by' => $currentUser->id,
            ]);

            // Reset form
            $this->reset(['selectedUserId', 'selectedRole']);

            session()->flash('message', 'Staff member added successfully.');

            // Refresh staff list if needed
            // You could add code here to refresh the staff list if it's being used in the view
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding staff member: ' . $e->getMessage());
        }
    }

    /**
     * Load the staff members for a shop
     */
    public function loadShopStaff()
    {
        if (!$this->selectedShopId) {
            return;
        }

        $shop = Shop::findOrFail($this->selectedShopId);
        
        // Get staff members with their roles - removed reference to non-existent column
        $this->shopStaff = $shop->staff()
            ->select('users.id', 'users.name', 'users.email', 'shop_user.role')
            ->get()
            ->map(function($staff) {
                // Since added_by column doesn't exist, we'll set a default value
                $staff->added_by_name = 'System';
                return $staff;
            });
    }

    /**
     * Load users that can be added as staff
     */
    public function loadAvailableUsers()
    {
        $shop = Shop::findOrFail($this->selectedShopId);
        $currentUserId = Auth::id();
        
        // Get current staff IDs to exclude
        $currentStaffIds = $shop->staff()->pluck('user_id')->toArray();
        $currentStaffIds[] = $shop->owner_id; // Also exclude the owner
        
        // Get users that aren't already staff or owner
        $query = User::whereNotIn('id', $currentStaffIds);
        
        // Filter by search term if provided
        if (!empty($this->searchTerm)) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->searchTerm}%")
                  ->orWhere('email', 'like', "%{$this->searchTerm}%");
            });
        }
        
        $this->availableUsers = $query->select('id', 'name', 'email')
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    /**
     * Search users as you type
     */
    public function updatedSearchTerm()
    {
        $this->loadAvailableUsers();
    }

    /**
     * Update a staff member's role
     */
    public function updateStaffRole($staffId, $role)
    {
        // Validate the role
        if (!in_array($role, ['staff', 'manager'])) {
            session()->flash('error', 'Invalid role specified.');
            return;
        }

        try {
            $shop = Shop::findOrFail($this->selectedShopId);
            $currentUser = Auth::user();
            
            // Check permission to update staff
            if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() && $shop->owner_id != $currentUser->id) {
                session()->flash('error', 'You do not have permission to update staff roles.');
                return;
            }
            
            // Update the role
            $shop->staff()->updateExistingPivot($staffId, [
                'role' => $role
            ]);
            
            session()->flash('message', 'Staff role updated successfully.');
            
            // Reload staff list to reflect changes
            $this->loadShopStaff();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating staff role: ' . $e->getMessage());
        }
    }
}
