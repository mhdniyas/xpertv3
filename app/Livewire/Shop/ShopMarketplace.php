<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use App\Models\Category;
use App\Models\Product;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ShopMarketplace extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedCategory = null;
    public $locationFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $viewMode = 'grid';
    public $selectedShop = null;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'selectedCategory' => ['except' => null],
        'locationFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function render()
    {
        // Use direct query rather than relation until we have data in the category_id column
        $categories = Category::whereHas('shopCategories', function($query) {
            $query->whereHas('shop', function($q) {
                $q->where('is_active', true)
                  ->where('status', 'approved');
            });
        })->get();

        // Get shop base query
        $shopsQuery = Shop::where('is_active', true)
            ->where('status', 'approved')
            ->when($this->searchTerm, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('address', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->selectedCategory, function($query) {
                $query->whereHas('shopCategories', function($q) {
                    $q->where('category_id', $this->selectedCategory);
                });
            })
            ->when($this->locationFilter, function($query) {
                $query->where('address', 'like', '%' . $this->locationFilter . '%');
            });

        // Get all unique locations for filter dropdown
        $locations = Shop::where('is_active', true)
            ->where('status', 'approved')
            ->whereNotNull('address')
            ->pluck('address')
            ->map(function($address) {
                // Extract city or main location from address
                $parts = explode(',', $address);
                return trim($parts[0]);
            })
            ->unique()
            ->values()
            ->toArray();

        // Apply pagination to the query
        $shops = $shopsQuery->orderBy($this->sortField, $this->sortDirection)
            ->paginate(12);

        $shopDetails = null;
        $shopProducts = collect();
        $shopCategories = collect();

        if ($this->selectedShop) {
            $shopDetails = Shop::with(['owner', 'staff'])->findOrFail($this->selectedShop);

            $shopProducts = ShopProduct::where('shop_id', $this->selectedShop)
                ->where('status', 'active')
                ->paginate(8);

            $shopCategories = ShopCategory::where('shop_id', $this->selectedShop)
                ->get();
        }

        return view('livewire.shop.shop-marketplace', [
            'shops' => $shops,
            'categories' => $categories,
            'locations' => $locations,
            'shopDetails' => $shopDetails,
            'shopProducts' => $shopProducts,
            'shopCategories' => $shopCategories
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function filterByLocation($location)
    {
        $this->locationFilter = $location;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->selectedCategory = null;
        $this->locationFilter = '';
        $this->resetPage();
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }

    public function viewShop($shopId)
    {
        $this->selectedShop = $shopId;
    }

    public function backToShopsList()
    {
        $this->selectedShop = null;
    }
}
