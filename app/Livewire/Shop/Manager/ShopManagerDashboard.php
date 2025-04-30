<?php

namespace App\Livewire\Shop\Manager;

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShopManagerDashboard extends Component
{
    // Shop properties
    public $shopId;
    public $shop;
    
    // Dashboard properties
    public $currentView = 'overview';
    public $views = [
        'overview' => 'Overview',
        'staff' => 'Staff Management',
        'categories' => 'Categories',
        'products' => 'Products',
        'orders' => 'Orders',
        'analytics' => 'Analytics',
        'settings' => 'Settings',
    ];
    
    // Statistics
    public $stats = [];
    
    public function mount($shopId)
    {
        $this->shopId = $shopId;
        $this->loadShop();
        $this->loadStatistics();
    }

    public function render()
    {
        return view('livewire.shop.manager.shop-manager-dashboard', [
            'shop' => $this->shop,
            'stats' => $this->stats,
        ]);
    }
    
    /**
     * Load shop data
     */
    public function loadShop()
    {
        $this->shop = Shop::with('owner')->findOrFail($this->shopId);
        
        // Check user permission to access this shop
        $currentUser = Auth::user();
        $canAccess = $currentUser->isAdmin() || 
                    $currentUser->isSuperadmin() || 
                    $this->shop->owner_id == $currentUser->id || 
                    $this->shop->staff()->where('user_id', $currentUser->id)->exists();
                    
        if (!$canAccess) {
            abort(403, 'You do not have permission to access this shop.');
        }
    }
    
    /**
     * Load shop statistics
     */
    public function loadStatistics()
    {
        // Basic statistics
        $this->stats = [
            'total_products' => ShopProduct::where('shop_id', $this->shopId)->count(),
            'active_products' => ShopProduct::where('shop_id', $this->shopId)
                ->where('status', 'active')
                ->count(),
            'total_categories' => ShopCategory::where('shop_id', $this->shopId)->count(),
            'staff_count' => $this->shop->staff()->count(),
            'recent_orders' => rand(5, 30), // Placeholder for actual order count
            'revenue_today' => rand(100, 1000), // Placeholder for actual revenue
            'revenue_month' => rand(1000, 10000), // Placeholder for actual revenue
            'low_stock_products' => ShopProduct::where('shop_id', $this->shopId)
                ->where('stock_quantity', '<=', 5)
                ->where('stock_quantity', '>', 0)
                ->count(),
            'out_of_stock' => ShopProduct::where('shop_id', $this->shopId)
                ->where('stock_quantity', '<=', 0)
                ->count(),
        ];
    }
    
    /**
     * Change current view
     */
    public function changeView($view)
    {
        if (array_key_exists($view, $this->views)) {
            $this->currentView = $view;
        }
    }
}