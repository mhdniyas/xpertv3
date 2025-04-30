<?php

namespace App\Livewire\Shop\Manager;

use App\Models\Shop;
use App\Models\Category;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    use WithPagination;

    // Shop and category properties
    public $shopId;
    public $shop;
    public $currentUser;
    public $shopCategories = [];
    public $selectedCategoryId;

    // Category form properties
    public $categoryId;
    public $categoryName;
    public $categoryDescription;
    public $parentCategoryId;
    public $selectedGlobalCategory;
    public $categoryStatus = 'active';

    // Modal controls
    public $isEditingCategory = false;
    public $showConfirmModal = false;
    public $categoryToDelete = null;
    public $searchTerm = '';

    protected $listeners = [
        'refreshCategories' => 'loadShopCategories'
    ];

    protected function rules()
    {
        return [
            'categoryName' => 'required|string|min:3|max:255',
            'categoryDescription' => 'nullable|string',
            'parentCategoryId' => 'nullable|integer|exists:shop_categories,id',
            'selectedGlobalCategory' => 'nullable|integer|exists:categories,id',
            'categoryStatus' => 'required|in:active,inactive',
        ];
    }

    public function mount($shopId)
    {
        $this->shopId = $shopId;
        $this->currentUser = Auth::user();
        $this->loadShop();
        $this->loadShopCategories();
    }

    public function render()
    {
        // Get global categories for mapping to shop categories
        $globalCategories = Category::active()->get();

        // Get parent categories for dropdown (excluding current category if editing)
        $parentCategories = ShopCategory::where('shop_id', $this->shopId)
            ->when($this->categoryId, function ($query) {
                return $query->where('id', '!=', $this->categoryId);
            })
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('livewire.shop.manager.category-manager', [
            'shop' => $this->shop,
            'shopCategories' => $this->shopCategories,
            'globalCategories' => $globalCategories,
            'parentCategories' => $parentCategories,
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
     * Load shop categories with product counts
     */
    public function loadShopCategories()
    {
        if (!$this->shopId) {
            return;
        }

        // Get all shop categories
        $query = ShopCategory::where('shop_id', $this->shopId);

        // Apply search if provided
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $shopCategories = $query->orderBy('parent_id', 'asc')
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
                'global_category_id' => $category->category_id,
                'created_at' => $category->created_at,
                'children' => [],
                'product_count' => 0
            ];
        }

        // Second pass: count products in each category
        foreach ($categoriesById as $id => $categoryData) {
            // Count products directly in this category
            $productCount = ShopProduct::where('shop_id', $this->shopId)
                ->where('shop_category_id', $id)
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
    }

    /**
     * Filter categories when search term changes
     */
    public function updatedSearchTerm()
    {
        $this->loadShopCategories();
    }

    /**
     * Open category edit modal
     */
    public function editCategory($id = null)
    {
        $this->resetValidation();
        $this->reset(['categoryId', 'categoryName', 'categoryDescription', 'parentCategoryId', 'selectedGlobalCategory', 'categoryStatus']);

        if ($id) {
            $category = ShopCategory::findOrFail($id);

            // Check permission to edit
            $shop = Shop::findOrFail($category->shop_id);
            $this->authorize('update', $shop);

            $this->categoryId = $category->id;
            $this->categoryName = $category->name;
            $this->categoryDescription = $category->description;
            $this->parentCategoryId = $category->parent_id;
            $this->selectedGlobalCategory = $category->category_id;
            $this->categoryStatus = $category->status;
        } else {
            $this->categoryStatus = 'active';
        }

        $this->isEditingCategory = true;
    }

    /**
     * Save category
     */
    public function saveCategory()
    {
        $this->validate();

        $shop = Shop::findOrFail($this->shopId);
        $currentUser = Auth::user();

        // Check authorization
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() &&
            !$shop->staff()->where('user_id', $currentUser->id)->exists() &&
            $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to manage categories for this shop.');
        }

        if ($this->categoryId) {
            // Update existing category
            $category = ShopCategory::findOrFail($this->categoryId);
        } else {
            // Create new category
            $category = new ShopCategory();
            $category->shop_id = $this->shopId;
            $category->created_by = $currentUser->id;
        }

        $category->name = $this->categoryName;
        $category->slug = Str::slug($this->categoryName);
        $category->description = $this->categoryDescription;
        $category->parent_id = $this->parentCategoryId;
        $category->category_id = $this->selectedGlobalCategory;
        $category->status = $this->categoryStatus;

        $category->save();

        $this->reset(['categoryId', 'categoryName', 'categoryDescription', 'parentCategoryId', 'selectedGlobalCategory']);
        $this->categoryStatus = 'active';
        $this->isEditingCategory = false;

        session()->flash('message', $this->categoryId ? 'Category updated successfully.' : 'Category created successfully.');

        // Refresh the categories list
        $this->loadShopCategories();
    }

    /**
     * Cancel category editing
     */
    public function cancelCategoryEdit()
    {
        $this->reset(['categoryId', 'categoryName', 'categoryDescription', 'parentCategoryId', 'selectedGlobalCategory']);
        $this->categoryStatus = 'active';
        $this->isEditingCategory = false;
    }

    /**
     * Confirm category deletion
     */
    public function confirmDeleteCategory($id)
    {
        $this->categoryToDelete = $id;
        $this->showConfirmModal = true;
    }

    /**
     * Cancel category deletion
     */
    public function cancelDeleteCategory()
    {
        $this->categoryToDelete = null;
        $this->showConfirmModal = false;
    }

    /**
     * Delete a category
     */
    public function deleteCategory()
    {
        if (!$this->categoryToDelete) {
            return;
        }

        $category = ShopCategory::findOrFail($this->categoryToDelete);
        $shop = Shop::findOrFail($category->shop_id);

        // Check authorization
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() &&
            !$shop->staff()->where('user_id', $currentUser->id)->exists() &&
            $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to delete categories for this shop.');
        }

        // Check if category has children
        if (ShopCategory::where('parent_id', $this->categoryToDelete)->exists()) {
            session()->flash('error', 'Cannot delete category with child categories. Remove child categories first.');
            $this->categoryToDelete = null;
            $this->showConfirmModal = false;
            return;
        }

        // Check if category has products
        if (ShopProduct::where('shop_category_id', $this->categoryToDelete)->exists()) {
            session()->flash('error', 'Cannot delete category with products. Remove products first or reassign them to another category.');
            $this->categoryToDelete = null;
            $this->showConfirmModal = false;
            return;
        }

        $category->delete();

        $this->categoryToDelete = null;
        $this->showConfirmModal = false;
        session()->flash('message', 'Category deleted successfully.');

        // Refresh the categories list
        $this->loadShopCategories();
    }

    /**
     * Update category status directly
     */
    public function updateCategoryStatus($categoryId, $newStatus)
    {
        if (!in_array($newStatus, ['active', 'inactive'])) {
            session()->flash('error', 'Invalid status.');
            return;
        }

        $category = ShopCategory::findOrFail($categoryId);

        // Check permission
        $shop = Shop::findOrFail($category->shop_id);
        $this->authorize('update', $shop);

        $category->status = $newStatus;
        $category->save();

        session()->flash('message', 'Category status updated successfully.');
        $this->loadShopCategories();
    }
}
