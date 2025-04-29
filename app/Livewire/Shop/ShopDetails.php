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

    // Shop detail properties
    public $selectedShopId = null;
    public $shopDetailView = 'overview'; // overview, inventory, categories, sales, manager
    public $productsByCategory = [];
    public $shopCategories = [];
    public $selectedCategoryId = null;
    public $showShopManager = false;
    public $searchTerm = '';

    // Shop statistics
    public $shopProducts;
    public $shopSales;
    public $shopVisits;

    protected $queryString = [
        'selectedShopId' => ['except' => null],
        'shopDetailView' => ['except' => 'overview'],
    ];

    public function mount($shopId = null)
    {
        $this->currentUser = Auth::user();

        if ($shopId) {
            $this->selectedShopId = $shopId;
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
            }
        }

        return view('livewire.shop.shop-details', [
            'currentUser' => $currentUser,
            'userShops' => $userShops,
            'selectedShop' => $selectedShop,
            'shopDetailView' => $this->shopDetailView,
            'productsByCategory' => $this->productsByCategory,
            'shopCategories' => $this->shopCategories,
            'selectedCategoryId' => $this->selectedCategoryId
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
        $this->shopCategories = ShopCategory::where('shop_id', $this->selectedShopId)
            ->count();

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

        // Get all categories that have products in this shop
        $shopCategories = ShopProduct::where('shop_products.shop_id', $this->selectedShopId)
            ->join('products', 'shop_products.global_product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name')
            ->distinct()
            ->orderBy('categories.name')
            ->get();

        // Group products by category
        $this->productsByCategory = [];

        foreach ($shopCategories as $category) {
            $products = ShopProduct::where('shop_products.shop_id', $this->selectedShopId)
                ->join('products', 'shop_products.global_product_id', '=', 'products.id')
                ->where('products.category_id', $category->id)
                ->select(
                    'shop_products.id',
                    'shop_products.global_product_id',
                    'products.name',
                    'products.description',
                    'shop_products.price',
                    'shop_products.stock_quantity as stock',
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
}
