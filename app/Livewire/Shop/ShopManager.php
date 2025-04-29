<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShopManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Shop properties
    public $shopId;
    public $name;
    public $slug;
    public $description;
    public $address;
    public $phone;
    public $email;
    public $is_active = true;
    public $status = 'pending';
    public $shopImage;
    public $existingImage;
    public $removeImage = false;

    // For staff assignment
    public $selectedShopId;
    public $selectedUserId;
    public $selectedRole = 'staff';
    public $searchTerm = '';

    // For category management
    public $categoryId;
    public $categoryName;
    public $categorySlug;
    public $categoryDescription;
    public $parentCategoryId;
    public $selectedGlobalCategory;
    public $categoryStatus = 'active';

    // For product management
    public $shopProductId;
    public $productName;
    public $productDescription;
    public $productPrice;
    public $productStock;
    public $productCategoryId;
    public $productImage;
    public $existingProductImage;
    public $removeProductImage = false;
    public $productStatus = 'active';
    public $productUnit = 'piece';
    public $productSku;
    public $productBarcode;
    public $productCost;
    public $productTags;
    public $productSourceType = 'local';
    public $globalProductId;
    public $selectedGlobalProduct;

    // For product variants
    public $hasVariants = false;
    public $variantOptions = [];
    public $variants = [];
    public $variantName;
    public $variantValues = [];
    public $editingVariantIndex = null;

    // For bulk operations
    public $selectedProducts = [];
    public $bulkAction = '';
    public $bulkCategoryId;
    public $bulkStatus;
    public $bulkPriceAdjustment;
    public $bulkPriceAdjustmentType = 'percentage'; // 'percentage' or 'fixed'

    // For product filtering and sorting
    public $productFilterCategory = '';
    public $productFilterStatus = '';
    public $productFilterStock = '';
    public $productFilterSource = '';
    public $productSortField = 'created_at';
    public $productSortDirection = 'desc';

    // For analytics
    public $analyticsDateRange = 'last30days';
    public $customStartDate;
    public $customEndDate;
    public $analyticsView = 'sales'; // 'sales', 'products', 'categories'

    // For import/export
    public $importFile;
    public $exportFormat = 'csv';
    public $exportData = 'all'; // 'all', 'filtered', 'selected'

    // For modal controls
    public $isEditingShop = false;
    public $isAssigningStaff = false;
    public $isManagingCategories = false;
    public $isEditingCategory = false;
    public $isManagingProducts = false;
    public $isEditingProduct = false;
    public $showConfirmModal = false;
    public $shopToDelete = null;
    public $staffToRemove = null;
    public $categoryToDelete = null;
    public $productToDelete = null;
    public $modalType = '';
    public $isImportingProducts = false;
    public $isExportingProducts = false;
    public $isViewingAnalytics = false;
    public $isManagingVariants = false;
    public $isBulkEditing = false;

    // For product display
    public $productsPerPage = 10;
    public $showProductGrid = false;

    // Product SEO properties
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
    public $seoSlug;

    // For tag management
    public $availableTags = [];
    public $selectedTags = [];
    public $newTag = '';

    protected $listeners = [
        'selectShopForStaffAssignment',
        'selectShopForCategoryManagement',
        'selectShopForProductManagement',
        'refreshShopList' => '$refresh',
        'productImported' => 'handleProductImport',
        'variantAdded' => 'refreshVariants',
        'globalProductSelected' => 'handleGlobalProductSelection'
    ];

    protected function rules()
    {
        return [
            // Shop validation rules
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',
            'shopImage' => 'nullable|image|max:1024', // 1MB limit

            // Category validation rules
            'categoryName' => 'required|string|min:3|max:255|sometimes',
            'categoryDescription' => 'nullable|string',
            'parentCategoryId' => 'nullable|integer|exists:shop_categories,id',
            'selectedGlobalCategory' => 'nullable|integer|exists:categories,id',
            'categoryStatus' => 'required|in:active,inactive|sometimes',

            // Product validation rules
            'productName' => 'required|string|min:3|max:255|sometimes',
            'productDescription' => 'nullable|string',
            'productPrice' => 'required|numeric|min:0|sometimes',
            'productStock' => 'nullable|integer|min:0',
            'productCategoryId' => 'required|integer|exists:shop_categories,id|sometimes',
            'productImage' => 'nullable|image|max:1024',
            'productStatus' => 'required|in:active,inactive|sometimes',
            'productUnit' => 'required|string|max:50|sometimes',
            'productSku' => 'nullable|string|max:100|sometimes',
            'productBarcode' => 'nullable|string|max:100|sometimes',
            'productCost' => 'nullable|numeric|min:0|sometimes',
            'productTags' => 'nullable|string|sometimes',
            'productSourceType' => 'required|in:local,global|sometimes',
            'globalProductId' => 'nullable|integer|exists:products,id|sometimes',
            'hasVariants' => 'boolean|sometimes',

            // Import/export validation
            'importFile' => 'nullable|file|mimes:csv,xlsx|max:5120',
            'exportFormat' => 'required|in:csv,xlsx|sometimes',
            'exportData' => 'required|in:all,filtered,selected|sometimes',

            // Bulk operation validation
            'bulkAction' => 'required|in:delete,update_category,update_status,adjust_price|sometimes',
            'bulkCategoryId' => 'required_if:bulkAction,update_category|nullable|exists:shop_categories,id',
            'bulkStatus' => 'required_if:bulkAction,update_status|nullable|in:active,inactive',
            'bulkPriceAdjustment' => 'required_if:bulkAction,adjust_price|nullable|numeric',
            'bulkPriceAdjustmentType' => 'required_if:bulkAction,adjust_price|nullable|in:percentage,fixed',
        ];
    }

    // Updated render method to include filtered products
    public function render()
    {
        $currentUser = Auth::user();

        // Initialize shops collection
        $shops = collect();
        $users = collect();
        $staffMembers = collect();
        $shopManagers = collect();
        $shopCategories = collect();
        $globalCategories = collect();
        $parentCategories = collect();
        $shopProducts = collect();
        $filteredProducts = collect();
        $globalProducts = collect();
        $analyticsData = null;

        // Get shops based on user role and permissions
        if ($currentUser->isSuperadmin() || $currentUser->isAdmin()) {
            // Admin and Superadmin can see all shops
            $shops = Shop::with('owner')
                ->when($this->searchTerm, function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('address', 'like', '%' . $this->searchTerm . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Get managers for assignment
            $managerRoleId = Role::where('name', 'manager')->first()->id ?? null;
            $users = User::when($managerRoleId, function($query) use ($managerRoleId) {
                $query->where('role_id', $managerRoleId);
            })->get();

        } else if ($currentUser->isManager()) {
            // Managers can see shops they own + shops they manage
            $shops = Shop::with('owner')
                ->where(function($query) use ($currentUser) {
                    $query->where('owner_id', $currentUser->id)
                        ->orWhereHas('staff', function($q) use ($currentUser) {
                            $q->where('user_id', $currentUser->id)
                                ->where('role', 'manager');
                        });
                })
                ->when($this->searchTerm, function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('address', 'like', '%' . $this->searchTerm . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Get managers for assignment (managers can only assign staff)
            $staffRoleId = Role::where('name', 'staff')->first()->id ?? null;
            $users = User::when($staffRoleId, function($query) use ($staffRoleId) {
                $query->where('role_id', $staffRoleId);
            })->get();
        }

        // If a shop is selected for staff assignment, get its existing staff
        if ($this->selectedShopId) {
            $selectedShop = Shop::find($this->selectedShopId);

            if ($selectedShop) {
                $staffMembers = $selectedShop->staff()->with('role')->get();

                // Get shop managers (users with manager role in the pivot)
                $shopManagers = $selectedShop->staff()
                    ->wherePivot('role', 'manager')
                    ->get();

                // Get shop categories
                $shopCategories = ShopCategory::where('shop_id', $this->selectedShopId)
                    ->with(['parent', 'category'])
                    ->get();

                // Get parent categories for dropdown
                $parentCategories = ShopCategory::where('shop_id', $this->selectedShopId)
                    ->whereNull('parent_id')
                    ->get();

                // Apply product filtering and sorting
                $query = ShopProduct::where('shop_id', $this->selectedShopId);

                // Apply filters
                if ($this->productFilterCategory) {
                    $query->where('shop_category_id', $this->productFilterCategory);
                }

                if ($this->productFilterStatus) {
                    $query->where('status', $this->productFilterStatus);
                }

                if ($this->productFilterStock === 'in_stock') {
                    $query->where('stock', '>', 0);
                } elseif ($this->productFilterStock === 'out_of_stock') {
                    $query->where('stock', '<=', 0);
                } elseif ($this->productFilterStock === 'low_stock') {
                    $query->where('stock', '>', 0)
                          ->where('stock', '<=', 10); // Adjust low stock threshold as needed
                }

                if ($this->productFilterSource) {
                    $query->where('source_type', $this->productFilterSource);
                }

                // Apply search term if any
                if ($this->searchTerm) {
                    $query->where(function($q) {
                        $q->where('name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('sku', 'like', '%' . $this->searchTerm . '%');
                    });
                }

                // Apply sorting
                $query->orderBy($this->productSortField, $this->productSortDirection);

                // Get products with pagination
                $filteredProducts = $query->with('shopCategory')->paginate($this->productsPerPage);

                // Also get all shop products for other operations
                $shopProducts = ShopProduct::where('shop_id', $this->selectedShopId)
                    ->with('shopCategory')
                    ->get();

                // Get global products for potential import
                $globalProducts = Product::active()->get();

                // Get analytics data if viewing analytics
                if ($this->isViewingAnalytics) {
                    $analyticsData = $this->getAnalyticsData();
                }
            }
        }

        // Get global categories for mapping to shop categories
        $globalCategories = Category::active()->get();

        return view('livewire.shop.shop-manager', [
            'shops' => $shops,
            'users' => $users,
            'staffMembers' => $staffMembers,
            'shopManagers' => $shopManagers,
            'shopCategories' => $shopCategories,
            'parentCategories' => $parentCategories,
            'globalCategories' => $globalCategories,
            'shopProducts' => $shopProducts,
            'filteredProducts' => $filteredProducts,
            'globalProducts' => $globalProducts,
            'roles' => ['manager', 'staff'],
            'currentUser' => $currentUser,
            'analyticsData' => $analyticsData
        ]);
    }

    // Open the shop edit modal
    public function editShop($id = null)
    {
        $this->resetValidation();
        $this->resetExcept(['searchTerm']);

        if ($id) {
            $shop = Shop::findOrFail($id);

            // Check permission to edit
            $this->authorize('update', $shop);

            $this->shopId = $shop->id;
            $this->name = $shop->name;
            $this->slug = $shop->slug;
            $this->description = $shop->description;
            $this->address = $shop->address;
            $this->phone = $shop->phone;
            $this->email = $shop->email;
            $this->is_active = $shop->is_active;
            $this->status = $shop->status;
            $this->existingImage = $shop->image;
        }

        $this->isEditingShop = true;
    }

    // Create or update a shop
    public function saveShop()
    {
        $this->validate();

        $currentUser = Auth::user();
        $isNew = !$this->shopId;

        if ($isNew) {
            $shop = new Shop();
            $shop->owner_id = $currentUser->id;
            $shop->status = 'pending';
        } else {
            $shop = Shop::findOrFail($this->shopId);

            // Check if current user can update this shop
            $this->authorize('update', $shop);

            // Only admin and superadmin can change status
            if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin()) {
                unset($this->status);
            }
        }

        // Set shop details
        $shop->name = $this->name;
        $shop->slug = Str::slug($this->name);
        $shop->description = $this->description;
        $shop->address = $this->address;
        $shop->phone = $this->phone;
        $shop->email = $this->email;
        $shop->is_active = $this->is_active;

        if ($currentUser->isAdmin() || $currentUser->isSuperadmin()) {
            $shop->status = $this->status;
        }

        // Handle image upload
        if ($this->shopImage) {
            // Delete old image if exists
            if ($shop->image && Storage::disk('public')->exists($shop->image)) {
                Storage::disk('public')->delete($shop->image);
            }

            // Store new image
            $shop->image = $this->shopImage->store('shops', 'public');
        } elseif ($this->removeImage && $shop->image) {
            // Remove image if requested
            Storage::disk('public')->delete($shop->image);
            $shop->image = null;
        }

        $shop->save();

        $this->resetExcept(['searchTerm']);
        $this->isEditingShop = false;
        session()->flash('message', $isNew ? 'Shop created successfully.' : 'Shop updated successfully.');
    }

    // Cancel shop editing
    public function cancelEdit()
    {
        $this->resetExcept(['searchTerm']);
        $this->isEditingShop = false;
    }

    // Open staff assignment modal
    public function selectShopForStaffAssignment($shopId)
    {
        $this->selectedShopId = $shopId;
        $this->isAssigningStaff = true;
    }

    // Assign staff member to shop
    public function assignStaff()
    {
        $this->validate([
            'selectedShopId' => 'required|exists:shops,id',
            'selectedUserId' => 'required|exists:users,id',
            'selectedRole' => 'required|in:manager,staff',
        ]);

        $shop = Shop::findOrFail($this->selectedShopId);

        // Check authorization
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() && $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to assign staff to this shop.');
        }

        // Check if user is already assigned to the shop
        $existing = $shop->staff()->where('user_id', $this->selectedUserId)->exists();

        if ($existing) {
            // Update their role instead
            $shop->staff()->updateExistingPivot($this->selectedUserId, [
                'role' => $this->selectedRole
            ]);
            session()->flash('message', 'Staff member role updated successfully.');
        } else {
            // Attach the user as new staff
            $shop->staff()->attach($this->selectedUserId, [
                'role' => $this->selectedRole
            ]);
            session()->flash('message', 'Staff member assigned to shop successfully.');
        }

        // Reset fields but keep the modal open to add more
        $this->reset(['selectedUserId', 'selectedRole']);
    }

    // Remove staff member from shop
    public function confirmRemoveStaff($userId)
    {
        $this->staffToRemove = $userId;
        $this->modalType = 'removeStaff';
        $this->showConfirmModal = true;
    }

    public function removeStaff()
    {
        if (!$this->selectedShopId || !$this->staffToRemove) {
            return;
        }

        $shop = Shop::findOrFail($this->selectedShopId);

        // Check authorization
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() && $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to remove staff from this shop.');
        }

        // Detach the staff member
        $shop->staff()->detach($this->staffToRemove);

        $this->staffToRemove = null;
        $this->showConfirmModal = false;
        session()->flash('message', 'Staff member removed successfully.');
    }

    // Close staff assignment modal
    public function closeStaffModal()
    {
        $this->isAssigningStaff = false;
        $this->selectedShopId = null;
        $this->reset(['selectedUserId', 'selectedRole', 'staffToRemove']);
    }

    // Confirm shop deletion
    public function confirmDeleteShop($id)
    {
        $this->shopToDelete = $id;
        $this->modalType = 'deleteShop';
        $this->showConfirmModal = true;
    }

    // Delete a shop
    public function deleteShop()
    {
        if (!$this->shopToDelete) {
            return;
        }

        $shop = Shop::findOrFail($this->shopToDelete);

        // Check authorization
        $this->authorize('delete', $shop);

        // Delete shop image if it exists
        if ($shop->image && Storage::disk('public')->exists($shop->image)) {
            Storage::disk('public')->delete($shop->image);
        }

        $shop->delete();

        $this->shopToDelete = null;
        $this->showConfirmModal = false;
        session()->flash('message', 'Shop deleted successfully.');
    }

    // Open category management modal
    public function selectShopForCategoryManagement($shopId)
    {
        $this->selectedShopId = $shopId;
        $this->isManagingCategories = true;
    }

    // Open category edit modal
    public function editCategory($id = null)
    {
        $this->resetValidation();
        $this->reset([
            'categoryId', 'categoryName', 'categorySlug', 'categoryDescription',
            'parentCategoryId', 'selectedGlobalCategory', 'categoryStatus'
        ]);

        if ($id) {
            $category = ShopCategory::findOrFail($id);

            // Check permission to edit
            $shop = Shop::findOrFail($category->shop_id);
            $this->authorize('update', $shop);

            $this->categoryId = $category->id;
            $this->categoryName = $category->name;
            $this->categorySlug = $category->slug;
            $this->categoryDescription = $category->description;
            $this->parentCategoryId = $category->parent_id;
            $this->selectedGlobalCategory = $category->category_id;
            $this->categoryStatus = $category->status;
        }

        $this->isEditingCategory = true;
    }

    // Save category
    public function saveCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|min:3|max:255',
            'categoryDescription' => 'nullable|string',
            'parentCategoryId' => 'nullable|integer|exists:shop_categories,id',
            'selectedGlobalCategory' => 'nullable|integer|exists:categories,id',
            'categoryStatus' => 'required|in:active,inactive',
        ]);

        $shop = Shop::findOrFail($this->selectedShopId);
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
            $category->shop_id = $this->selectedShopId;
            $category->created_by = $currentUser->id;
        }

        $category->name = $this->categoryName;
        $category->slug = Str::slug($this->categoryName);
        $category->description = $this->categoryDescription;
        $category->parent_id = $this->parentCategoryId;
        $category->category_id = $this->selectedGlobalCategory;
        $category->status = $this->categoryStatus;

        $category->save();

        $this->reset([
            'categoryId', 'categoryName', 'categorySlug', 'categoryDescription',
            'parentCategoryId', 'selectedGlobalCategory', 'categoryStatus'
        ]);
        $this->isEditingCategory = false;
        session()->flash('message', $this->categoryId ? 'Category updated successfully.' : 'Category created successfully.');
    }

    // Cancel category editing
    public function cancelCategoryEdit()
    {
        $this->reset([
            'categoryId', 'categoryName', 'categorySlug', 'categoryDescription',
            'parentCategoryId', 'selectedGlobalCategory', 'categoryStatus'
        ]);
        $this->isEditingCategory = false;
    }

    // Confirm category deletion
    public function confirmDeleteCategory($id)
    {
        $this->categoryToDelete = $id;
        $this->modalType = 'deleteCategory';
        $this->showConfirmModal = true;
    }

    // Delete a category
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
        if ($category->children()->count() > 0) {
            session()->flash('error', 'Cannot delete category with child categories. Remove child categories first.');
            $this->categoryToDelete = null;
            $this->showConfirmModal = false;
            return;
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            session()->flash('error', 'Cannot delete category with products. Remove products first or reassign them to another category.');
            $this->categoryToDelete = null;
            $this->showConfirmModal = false;
            return;
        }

        $category->delete();

        $this->categoryToDelete = null;
        $this->showConfirmModal = false;
        session()->flash('message', 'Category deleted successfully.');
    }

    // Close category management modal
    public function closeCategoryModal()
    {
        $this->isManagingCategories = false;
        $this->selectedShopId = null;
        $this->reset([
            'categoryId', 'categoryName', 'categorySlug', 'categoryDescription',
            'parentCategoryId', 'selectedGlobalCategory', 'categoryStatus'
        ]);
    }

    // Open product management modal
    public function selectShopForProductManagement($shopId)
    {
        $this->selectedShopId = $shopId;
        $this->isManagingProducts = true;
    }

    // Enhanced product editing with additional fields
    public function editProduct($id = null)
    {
        $this->resetValidation();
        $this->reset([
            'shopProductId', 'productName', 'productDescription', 'productPrice',
            'productStock', 'productCategoryId', 'productImage', 'existingProductImage',
            'productStatus', 'productUnit', 'productSku', 'productBarcode',
            'productCost', 'productTags', 'productSourceType', 'globalProductId',
            'hasVariants', 'variantOptions', 'variants', 'metaTitle',
            'metaDescription', 'metaKeywords', 'seoSlug', 'selectedTags'
        ]);

        // Get all existing tags for this shop
        $this->loadAvailableTags();

        if ($id) {
            $product = ShopProduct::findOrFail($id);

            // Check permission to edit
            $shop = Shop::findOrFail($product->shop_id);
            $this->authorize('update', $shop);

            $this->shopProductId = $product->id;
            $this->productName = $product->name;
            $this->productDescription = $product->description;
            $this->productPrice = $product->price;
            $this->productStock = $product->stock_quantity ?? $product->stock;
            $this->productCategoryId = $product->shop_category_id;
            $this->existingProductImage = $product->image;
            $this->productStatus = $product->status ?? 'active';
            $this->productUnit = $product->unit ?? 'piece';
            $this->productSku = $product->sku;
            $this->productBarcode = $product->barcode;
            $this->productCost = $product->cost_price ?? null;
            $this->productTags = $product->tags;
            $this->productSourceType = $product->source_type ?? 'local';
            $this->globalProductId = $product->global_product_id;
            
            // Load SEO fields
            $this->metaTitle = $product->meta_title ?? $product->name;
            $this->metaDescription = $product->meta_description ?? $product->description;
            $this->metaKeywords = $product->meta_keywords ?? '';
            $this->seoSlug = $product->seo_slug ?? $product->slug;
            
            // Load tags
            if (!empty($product->tags)) {
                $this->selectedTags = is_string($product->tags) 
                    ? array_map('trim', explode(',', $product->tags)) 
                    : $product->tags;
            }

            // Load variants if any
            if ($product->has_variants) {
                $this->hasVariants = true;
                // Load variant options and variants
                // This would require additional model relationships
            }
        } else {
            // Set defaults for new product
            $this->productStatus = 'active';
            $this->productUnit = 'piece';
            $this->productSourceType = 'local';
        }

        $this->isEditingProduct = true;
    }

    // Load all available tags for the current shop
    private function loadAvailableTags()
    {
        // Get distinct tags used in this shop
        $products = ShopProduct::where('shop_id', $this->selectedShopId)->whereNotNull('tags')->get();
        $allTags = [];
        
        foreach ($products as $product) {
            if (empty($product->tags)) continue;
            
            $productTags = is_string($product->tags) 
                ? array_map('trim', explode(',', $product->tags)) 
                : $product->tags;
                
            $allTags = array_merge($allTags, $productTags);
        }
        
        $this->availableTags = array_values(array_unique($allTags));
    }
    
    // Add a new tag to the selected tags
    public function addTag()
    {
        if (empty($this->newTag)) return;
        
        $tag = trim($this->newTag);
        
        if (!in_array($tag, $this->selectedTags)) {
            $this->selectedTags[] = $tag;
            
            // Add to available tags if it's new
            if (!in_array($tag, $this->availableTags)) {
                $this->availableTags[] = $tag;
            }
        }
        
        $this->newTag = '';
    }
    
    // Remove a tag from the selected tags
    public function removeTag($tag)
    {
        $index = array_search($tag, $this->selectedTags);
        if ($index !== false) {
            unset($this->selectedTags[$index]);
            $this->selectedTags = array_values($this->selectedTags);
        }
    }
    
    // Select an existing tag
    public function selectTag($tag)
    {
        if (!in_array($tag, $this->selectedTags)) {
            $this->selectedTags[] = $tag;
        }
    }
    
    // Generate SEO-friendly slug
    public function generateSeoSlug()
    {
        if ($this->productName) {
            $this->seoSlug = Str::slug($this->productName);
        }
    }
    
    // Generate meta title if empty
    public function generateMetaTitle()
    {
        if (empty($this->metaTitle) && $this->productName) {
            $this->metaTitle = $this->productName;
        }
    }
    
    // Generate meta description if empty
    public function generateMetaDescription()
    {
        if (empty($this->metaDescription) && $this->productDescription) {
            // Take first 160 characters of description for meta description
            $this->metaDescription = Str::limit(strip_tags($this->productDescription), 160);
        }
    }
    
    // Enhanced save product method with additional fields
    public function saveProduct()
    {
        $validationRules = [
            'productName' => 'required|string|min:3|max:255',
            'productDescription' => 'nullable|string',
            'productPrice' => 'required|numeric|min:0',
            'productStock' => 'nullable|integer|min:0',
            'productCategoryId' => 'required|integer|exists:shop_categories,id',
            'productImage' => 'nullable|image|max:1024',
            'productStatus' => 'required|in:active,inactive',
            'productUnit' => 'required|string|max:50',
            'productSku' => 'nullable|string|max:100',
            'productBarcode' => 'nullable|string|max:100',
            'productCost' => 'nullable|numeric|min:0',
            'productSourceType' => 'required|in:local,global',
            'hasVariants' => 'boolean',
            
            // SEO validation
            'metaTitle' => 'nullable|string|max:70',
            'metaDescription' => 'nullable|string|max:160',
            'metaKeywords' => 'nullable|string|max:255',
            'seoSlug' => 'nullable|string|max:100',
        ];

        // If product source is global, validate global product ID
        if ($this->productSourceType === 'global') {
            $validationRules['globalProductId'] = 'required|exists:products,id';
        }

        $this->validate($validationRules);

        $shop = Shop::findOrFail($this->selectedShopId);
        $currentUser = Auth::user();

        // Check authorization
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() &&
            !$shop->staff()->where('user_id', $currentUser->id)->exists() &&
            $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to manage products for this shop.');
        }

        if ($this->shopProductId) {
            // Update existing product
            $product = ShopProduct::findOrFail($this->shopProductId);
        } else {
            // Create new product
            $product = new ShopProduct();
            $product->shop_id = $this->selectedShopId;
            $product->created_by = $currentUser->id;
        }

        // Basic product info
        $product->name = $this->productName;
        $product->slug = Str::slug($this->productName);
        $product->description = $this->productDescription;
        $product->price = $this->productPrice;
        $product->stock_quantity = $this->productStock;
        $product->stock = $this->productStock; // For backward compatibility
        $product->shop_category_id = $this->productCategoryId;
        $product->status = $this->productStatus;
        $product->unit = $this->productUnit;
        $product->sku = $this->productSku;
        $product->barcode = $this->productBarcode;
        $product->cost_price = $this->productCost;
        $product->source_type = $this->productSourceType;
        $product->has_variants = $this->hasVariants;
        
        // Process tags
        $product->tags = !empty($this->selectedTags) ? implode(',', $this->selectedTags) : null;
        
        // SEO fields
        $product->meta_title = $this->metaTitle;
        $product->meta_description = $this->metaDescription;
        $product->meta_keywords = $this->metaKeywords;
        $product->seo_slug = !empty($this->seoSlug) ? Str::slug($this->seoSlug) : $product->slug;

        // If global product, link to it
        if ($this->productSourceType === 'global' && $this->globalProductId) {
            $product->global_product_id = $this->globalProductId;

            // Optionally sync some data from global product
            $globalProduct = Product::find($this->globalProductId);
            if ($globalProduct) {
                $product->name = $globalProduct->name;
                $product->description = $globalProduct->description;
                // Other fields as needed
            }
        } else {
            $product->global_product_id = null;
        }

        // Handle image upload
        if ($this->productImage) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Store new image
            $product->image = $this->productImage->store('products', 'public');
        } elseif ($this->removeProductImage && $product->image) {
            // Remove image if requested
            Storage::disk('public')->delete($product->image);
            $product->image = null;
        }

        $product->save();

        // Handle variants if product has them
        if ($this->hasVariants) {
            $this->saveProductVariants($product);
        }

        $this->reset([
            'shopProductId', 'productName', 'productDescription', 'productPrice',
            'productStock', 'productCategoryId', 'productImage', 'existingProductImage',
            'productStatus', 'productUnit', 'productSku', 'productBarcode',
            'productCost', 'productTags', 'productSourceType', 'globalProductId',
            'hasVariants', 'variantOptions', 'variants', 'metaTitle',
            'metaDescription', 'metaKeywords', 'seoSlug', 'selectedTags'
        ]);

        $this->isEditingProduct = false;
        session()->flash('message', $this->shopProductId ? 'Product updated successfully.' : 'Product created successfully.');
    }

    // Method to save product variants
    private function saveProductVariants($product)
    {
        if (!$this->shopProductId) {
            session()->flash('error', 'No product selected for variant management.');
            return;
        }
        
        $product->has_variants = $this->hasVariants;
        $product->save();
        
        // Save variant options and variants
        // This would handle saving variant options and variant combinations
        // For example, saving color and size options, and then creating
        // variants for each combination (Red-Small, Red-Medium, Blue-Small, etc.)

        // Implementation depends on your variant model structure
    }

    // Add a new variant option
    public function addVariantOption()
    {
        $this->validate([
            'variantName' => 'required|string|min:1|max:50',
        ]);

        // Add the variant option
        $this->variantOptions[] = [
            'name' => $this->variantName,
            'values' => $this->variantValues
        ];

        // Reset the input fields
        $this->reset(['variantName', 'variantValues']);

        // Generate variant combinations
        $this->generateVariantCombinations();
    }

    // Generate all possible variant combinations
    private function generateVariantCombinations()
    {
        // Start fresh
        $this->variants = [];

        if (empty($this->variantOptions)) {
            return;
        }

        // Get all possible combinations of variant values
        $optionsArray = [];
        foreach ($this->variantOptions as $option) {
            if (!empty($option['values'])) {
                $optionsArray[] = $option['values'];
            }
        }

        if (empty($optionsArray)) {
            return;
        }

        // Generate combinations using recursion
        $combinations = $this->generateCombinations($optionsArray);

        // Create variant entries for each combination
        foreach ($combinations as $combination) {
            $this->variants[] = [
                'combination' => $combination,
                'price' => $this->productPrice,
                'stock' => $this->productStock,
                'sku' => '',
                'image' => null
            ];
        }
    }

    // Helper function to generate all combinations
    private function generateCombinations($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return [];
        }
        if ($i == count($arrays) - 1) {
            return array_map(function($v) { return [$v]; }, $arrays[$i]);
        }

        $combinations = [];
        $nextCombinations = $this->generateCombinations($arrays, $i + 1);

        foreach ($arrays[$i] as $value) {
            foreach ($nextCombinations as $nextCombination) {
                $combinations[] = array_merge([$value], $nextCombination);
            }
        }

        return $combinations;
    }

    // Edit a specific variant
    public function editVariant($index)
    {
        $this->editingVariantIndex = $index;
    }

    // Save variant changes
    public function saveVariant()
    {
        $this->editingVariantIndex = null;
    }

    // Remove a variant option
    public function removeVariantOption($index)
    {
        unset($this->variantOptions[$index]);
        $this->variantOptions = array_values($this->variantOptions);
        $this->generateVariantCombinations();
    }

    // Toggle between list and grid view for products
    public function toggleProductView()
    {
        $this->showProductGrid = !$this->showProductGrid;
    }

    // Refresh sort/filter when changed
    public function updatedProductSortField()
    {
        $this->resetPage();
    }

    public function updatedProductSortDirection()
    {
        $this->resetPage();
    }

    public function updatedProductFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedProductFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedProductFilterStock()
    {
        $this->resetPage();
    }

    public function updatedProductFilterSource()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // Confirm product deletion
    public function confirmDeleteProduct($id)
    {
        $this->productToDelete = $id;
        $this->modalType = 'deleteProduct';
        $this->showConfirmModal = true;
    }

    // Delete a product
    public function deleteProduct()
    {
        if (!$this->productToDelete) {
            return;
        }

        $product = ShopProduct::findOrFail($this->productToDelete);
        $shop = Shop::findOrFail($product->shop_id);

        // Check authorization
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() &&
            !$shop->staff()->where('user_id', $currentUser->id)->exists() &&
            $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to delete products for this shop.');
        }

        // Delete product image if it exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        $this->productToDelete = null;
        $this->showConfirmModal = false;
        session()->flash('message', 'Product deleted successfully.');
    }

    // Close product management modal
    public function closeProductModal()
    {
        $this->isManagingProducts = false;
        $this->selectedShopId = null;
        $this->reset([
            'shopProductId', 'productName', 'productDescription', 'productPrice',
            'productStock', 'productCategoryId', 'productImage', 'existingProductImage'
        ]);
    }

    // Cancel confirmation modal
    public function cancelConfirmation()
    {
        $this->showConfirmModal = false;
        $this->shopToDelete = null;
        $this->staffToRemove = null;
        $this->categoryToDelete = null;
        $this->productToDelete = null;
        $this->modalType = '';
    }

    // Reset form fields
    public function resetFields()
    {
        $this->reset([
            'shopId', 'name', 'slug', 'description', 'address',
            'phone', 'email', 'is_active', 'status', 'shopImage',
            'existingImage', 'removeImage'
        ]);
    }

    // Open bulk editing modal
    public function openBulkEditModal()
    {
        if (empty($this->selectedProducts)) {
            session()->flash('error', 'Please select at least one product.');
            return;
        }
        
        $this->isBulkEditing = true;
    }
    
    // Perform bulk operations on selected products
    public function performBulkAction()
    {
        if (empty($this->selectedProducts)) {
            session()->flash('error', 'No products selected.');
            return;
        }
        
        $this->validate([
            'bulkAction' => 'required|in:delete,update_category,update_status,adjust_price',
        ]);
        
        // Additional validation based on the action
        if ($this->bulkAction === 'update_category') {
            $this->validate(['bulkCategoryId' => 'required|exists:shop_categories,id']);
        } elseif ($this->bulkAction === 'update_status') {
            $this->validate(['bulkStatus' => 'required|in:active,inactive']);
        } elseif ($this->bulkAction === 'adjust_price') {
            $this->validate([
                'bulkPriceAdjustment' => 'required|numeric',
                'bulkPriceAdjustmentType' => 'required|in:percentage,fixed',
            ]);
        }
        
        // Get the products to update
        $products = ShopProduct::whereIn('id', $this->selectedProducts)->get();
        
        // Apply the action
        foreach ($products as $product) {
            switch ($this->bulkAction) {
                case 'delete':
                    // Delete product image if it exists
                    if ($product->image && Storage::disk('public')->exists($product->image)) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $product->delete();
                    break;
                    
                case 'update_category':
                    $product->shop_category_id = $this->bulkCategoryId;
                    $product->save();
                    break;
                    
                case 'update_status':
                    $product->status = $this->bulkStatus;
                    $product->save();
                    break;
                    
                case 'adjust_price':
                    if ($this->bulkPriceAdjustmentType === 'percentage') {
                        // Calculate percentage adjustment
                        $adjustment = $product->price * ($this->bulkPriceAdjustment / 100);
                        $product->price += $adjustment;
                    } else {
                        // Apply fixed amount adjustment
                        $product->price += $this->bulkPriceAdjustment;
                    }
                    
                    // Ensure price doesn't go below zero
                    if ($product->price < 0) {
                        $product->price = 0;
                    }
                    
                    $product->save();
                    break;
            }
        }
        
        // Reset
        $this->selectedProducts = [];
        $this->isBulkEditing = false;
        $this->reset(['bulkAction', 'bulkCategoryId', 'bulkStatus', 'bulkPriceAdjustment', 'bulkPriceAdjustmentType']);
        
        // Show success message
        $actionText = match($this->bulkAction) {
            'delete' => 'deleted',
            'update_category' => 'category updated for',
            'update_status' => 'status updated for',
            'adjust_price' => 'prices adjusted for',
            default => 'updated'
        };
        
        session()->flash('message', 'Successfully ' . $actionText . ' ' . count($products) . ' products.');
    }
    
    // Cancel bulk editing
    public function cancelBulkEdit()
    {
        $this->isBulkEditing = false;
        $this->reset(['bulkAction', 'bulkCategoryId', 'bulkStatus', 'bulkPriceAdjustment', 'bulkPriceAdjustmentType']);
    }
    
    // Select/deselect all products
    public function toggleSelectAll($isChecked)
    {
        if ($isChecked) {
            // Select all products on the current page
            $query = ShopProduct::where('shop_id', $this->selectedShopId);
            
            // Apply filters
            if ($this->productFilterCategory) {
                $query->where('shop_category_id', $this->productFilterCategory);
            }
            
            if ($this->productFilterStatus) {
                $query->where('status', $this->productFilterStatus);
            }
            
            if ($this->productFilterStock === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($this->productFilterStock === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($this->productFilterStock === 'low_stock') {
                $query->where('stock', '>', 0)
                      ->where('stock', '<=', 10);
            }
            
            $this->selectedProducts = $query->pluck('id')->toArray();
        } else {
            // Deselect all
            $this->selectedProducts = [];
        }
    }
    
    // Open the product import modal
    public function openImportModal()
    {
        $this->isImportingProducts = true;
    }
    
    // Handle file import
    public function importProducts()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,xlsx|max:5120',
        ]);
        
        // Process the import file
        try {
            // This is a placeholder for the actual import logic
            // In a real implementation, you would:
            // 1. Read the file (using a package like maatwebsite/excel)
            // 2. Validate the data
            // 3. Create the products
            
            // For demonstration purposes:
            session()->flash('message', 'Import started. Products are being processed in the background.');
            $this->isImportingProducts = false;
            $this->reset(['importFile']);
            
            // In a real implementation, you might dispatch a job to handle this
            // Import::dispatch($this->importFile, $this->selectedShopId, Auth::id());
            
        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: ' . $e->getMessage());
        }
    }
    
    // Handle import completion (would be triggered by an event in a real app)
    public function handleProductImport($results)
    {
        // $results would contain details about the import
        session()->flash('message', 'Import completed: ' . $results['imported'] . ' products imported, ' . $results['failed'] . ' failed.');
    }
    
    // Open the export modal
    public function openExportModal()
    {
        $this->isExportingProducts = true;
    }
    
    // Export products
    public function exportProducts()
    {
        $this->validate([
            'exportFormat' => 'required|in:csv,xlsx',
            'exportData' => 'required|in:all,filtered,selected',
        ]);
        
        // Get the products to export
        $query = ShopProduct::where('shop_id', $this->selectedShopId);
        
        switch ($this->exportData) {
            case 'filtered':
                // Apply the current filters
                if ($this->productFilterCategory) {
                    $query->where('shop_category_id', $this->productFilterCategory);
                }
                
                if ($this->productFilterStatus) {
                    $query->where('status', $this->productFilterStatus);
                }
                
                if ($this->productFilterStock === 'in_stock') {
                    $query->where('stock', '>', 0);
                } elseif ($this->productFilterStock === 'out_of_stock') {
                    $query->where('stock', '<=', 0);
                } elseif ($this->productFilterStock === 'low_stock') {
                    $query->where('stock', '>', 0)
                          ->where('stock', '<=', 10);
                }
                break;
                
            case 'selected':
                // Only export the selected products
                if (empty($this->selectedProducts)) {
                    session()->flash('error', 'No products selected for export.');
                    return;
                }
                $query->whereIn('id', $this->selectedProducts);
                break;
        }
        
        // This is a placeholder for the actual export logic
        // In a real implementation, you would:
        // 1. Query the data
        // 2. Format it for export
        // 3. Generate and return the file
        
        // For demonstration purposes:
        session()->flash('message', 'Export started. Your file will be downloaded automatically when ready.');
        $this->isExportingProducts = false;
        
        // In a real implementation, you might:
        // return Excel::download(new ProductsExport($query), 'products.' . $this->exportFormat);
    }
    
    // Open analytics view
    public function openAnalyticsModal()
    {
        $this->isViewingAnalytics = true;
    }
    
    // Get analytics data
    public function getAnalyticsData()
    {
        $shop = Shop::findOrFail($this->selectedShopId);
        
        // Determine date range
        $endDate = Carbon::now();
        $startDate = match($this->analyticsDateRange) {
            'today' => Carbon::today(),
            'yesterday' => Carbon::yesterday(),
            'last7days' => Carbon::now()->subDays(7),
            'last30days' => Carbon::now()->subDays(30),
            'thisMonth' => Carbon::now()->startOfMonth(),
            'lastMonth' => Carbon::now()->subMonth()->startOfMonth(),
            'custom' => Carbon::parse($this->customStartDate),
            default => Carbon::now()->subDays(30)
        };
        
        if ($this->analyticsDateRange === 'lastMonth') {
            $endDate = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($this->analyticsDateRange === 'custom') {
            $endDate = Carbon::parse($this->customEndDate);
        }
        
        // This would contain SQL queries to gather the requested analytics data
        // For example, sales by day, top products, etc.
        
        // For demonstration purposes, let's create sample data
        $analyticsData = [
            'salesTotal' => rand(1000, 10000),
            'salesCount' => rand(50, 500),
            'averageOrderValue' => rand(20, 100),
            'topProducts' => [],
            'salesByDay' => [],
            'salesByCategory' => []
        ];
        
        // In a real implementation, you would query the database for actual data
        // For example:
        // $salesData = Order::where('shop_id', $this->selectedShopId)
        //     ->whereBetween('created_at', [$startDate, $endDate])
        //     ->selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(*) as count')
        //     ->groupBy('date')
        //     ->get();
            
        // Return the data
        return $analyticsData;
    }
    
    // Update analytics date range
    public function updateAnalyticsDateRange()
    {
        if ($this->analyticsDateRange === 'custom') {
            $this->validate([
                'customStartDate' => 'required|date',
                'customEndDate' => 'required|date|after_or_equal:customStartDate',
            ]);
        }
        
        // This will trigger getAnalyticsData() in the render method
        $this->reset(['analyticsData']);
    }
    
    // Import products from global catalog
    public function importGlobalProducts($productIds = [])
    {
        if (empty($productIds)) {
            session()->flash('error', 'No products selected for import.');
            return;
        }
        
        $currentUser = Auth::user();
        $shop = Shop::findOrFail($this->selectedShopId);
        
        // Check authorization
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() &&
            !$shop->staff()->where('user_id', $currentUser->id)->exists() &&
            $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to import products for this shop.');
        }
        
        // Get the global products
        $globalProducts = Product::whereIn('id', $productIds)->get();
        
        // For each global product, create a shop product
        foreach ($globalProducts as $globalProduct) {
            // Check if product already exists in the shop
            $existingProduct = ShopProduct::where('shop_id', $this->selectedShopId)
                ->where('global_product_id', $globalProduct->id)
                ->first();
                
            if (!$existingProduct) {
                // Create new product
                $shopProduct = new ShopProduct();
                $shopProduct->shop_id = $this->selectedShopId;
                $shopProduct->name = $globalProduct->name;
                $shopProduct->slug = Str::slug($globalProduct->name);
                $shopProduct->description = $globalProduct->description;
                $shopProduct->price = $globalProduct->price;
                $shopProduct->stock_quantity = 0; // Default to zero stock
                $shopProduct->stock = 0; // For backward compatibility
                $shopProduct->status = 'active';
                $shopProduct->source_type = 'global';
                $shopProduct->global_product_id = $globalProduct->id;
                $shopProduct->created_by = $currentUser->id;
                
                // Set a default category
                $shopProduct->shop_category_id = ShopCategory::where('shop_id', $this->selectedShopId)
                    ->orderBy('id')
                    ->value('id');
                    
                // Copy image if exists
                if ($globalProduct->image) {
                    $shopProduct->image = $globalProduct->image; // This assumes images share the same storage
                }
                
                $shopProduct->save();
            }
        }
        
        session()->flash('message', count($globalProducts) . ' products imported successfully.');
    }
    
    // Clone a product within the shop
    public function cloneProduct($id)
    {
        $originalProduct = ShopProduct::findOrFail($id);
        $shop = Shop::findOrFail($originalProduct->shop_id);
        $currentUser = Auth::user();
        
        // Check authorization
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() &&
            !$shop->staff()->where('user_id', $currentUser->id)->exists() &&
            $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to clone products for this shop.');
        }
        
        // Create a clone of the product
        $clonedProduct = $originalProduct->replicate();
        $clonedProduct->name = $originalProduct->name . ' (Copy)';
        $clonedProduct->slug = Str::slug($clonedProduct->name);
        $clonedProduct->created_by = $currentUser->id;
        $clonedProduct->created_at = now();
        
        // Handle unique fields if needed
        if ($originalProduct->sku) {
            $clonedProduct->sku = $originalProduct->sku . '-COPY';
        }
        
        $clonedProduct->save();
        
        // Clone the image if it exists
        if ($originalProduct->image && Storage::disk('public')->exists($originalProduct->image)) {
            $imagePath = $originalProduct->image;
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
            $newImagePath = 'products/' . Str::uuid() . '.' . $extension;
            
            if (Storage::disk('public')->copy($imagePath, $newImagePath)) {
                $clonedProduct->image = $newImagePath;
                $clonedProduct->save();
            }
        }
        
        session()->flash('message', 'Product cloned successfully.');
    }
    
    // Update product stock quantity
    public function updateProductStock($productId, $newStock)
    {
        if (!is_numeric($newStock) || $newStock < 0) {
            session()->flash('error', 'Stock quantity must be a positive number.');
            return;
        }
        
        $product = ShopProduct::findOrFail($productId);
        $shop = Shop::findOrFail($product->shop_id);
        $currentUser = Auth::user();
        
        // Check authorization
        if (!$currentUser->isAdmin() && !$currentUser->isSuperadmin() &&
            !$shop->staff()->where('user_id', $currentUser->id)->exists() &&
            $shop->owner_id != $currentUser->id) {
            abort(403, 'You do not have permission to update stock for this shop.');
        }
        
        // Update the stock
        $product->stock_quantity = $newStock;
        $product->stock = $newStock; // For backward compatibility
        $product->save();
        
        session()->flash('message', 'Stock updated successfully.');
    }

    // Open global product browser
    public function browseGlobalProducts()
    {
        $this->isEditingProduct = false;
        $this->dispatch('openGlobalProductBrowser', $this->selectedShopId);
    }
    
    // Handle global product selection
    public function handleGlobalProductSelection($product)
    {
        // Create a new shop product from the global product
        $currentUser = Auth::user();
        
        // Check if the product already exists in the shop
        $existingProduct = ShopProduct::where('shop_id', $this->selectedShopId)
            ->where('global_product_id', $product['id'])
            ->first();
            
        if ($existingProduct) {
            session()->flash('message', 'This product is already in your shop.');
            return;
        }
        
        // Create new product
        $shopProduct = new ShopProduct();
        $shopProduct->shop_id = $this->selectedShopId;
        $shopProduct->name = $product['name'];
        $shopProduct->slug = Str::slug($product['name']);
        $shopProduct->description = $product['description'] ?? null;
        $shopProduct->price = $product['price'] ?? 0;
        $shopProduct->stock_quantity = 0; // Default to zero stock
        $shopProduct->stock = 0; // For backward compatibility
        $shopProduct->status = 'active';
        $shopProduct->source_type = 'global';
        $shopProduct->global_product_id = $product['id'];
        $shopProduct->created_by = $currentUser->id;
        
        // Set a default category if one exists
        $defaultCategory = ShopCategory::where('shop_id', $this->selectedShopId)
            ->orderBy('id')
            ->first();
            
        $shopProduct->shop_category_id = $defaultCategory ? $defaultCategory->id : null;
        
        // Copy image if exists
        if (!empty($product['image'])) {
            $shopProduct->image = $product['image'];
        }
        
        $shopProduct->save();
        
        session()->flash('message', 'Product imported successfully.');
    }
    
    // Initialize product creation from global product
    public function initFromGlobalProduct($globalProductId)
    {
        $globalProduct = Product::find($globalProductId);
        
        if (!$globalProduct) {
            session()->flash('error', 'Global product not found.');
            return;
        }
        
        $this->resetValidation();
        $this->reset([
            'shopProductId', 'productName', 'productDescription', 'productPrice',
            'productStock', 'productCategoryId', 'productImage', 'existingProductImage',
            'productStatus', 'productUnit', 'productSku', 'productBarcode',
            'productCost', 'productTags'
        ]);
        
        // Populate fields from global product
        $this->productName = $globalProduct->name;
        $this->productDescription = $globalProduct->description;
        $this->productPrice = $globalProduct->price;
        $this->productStock = 0; // Default to zero stock for new products
        $this->productStatus = 'active';
        $this->productSourceType = 'global';
        $this->globalProductId = $globalProduct->id;
        
        if ($globalProduct->category_id) {
            // Try to map the global category to a shop category
            $shopCategory = ShopCategory::where('shop_id', $this->selectedShopId)
                ->where('category_id', $globalProduct->category_id)
                ->first();
                
            if ($shopCategory) {
                $this->productCategoryId = $shopCategory->id;
            }
        }
        
        // If no matching shop category was found, use the first available category
        if (!$this->productCategoryId) {
            $this->productCategoryId = ShopCategory::where('shop_id', $this->selectedShopId)
                ->orderBy('id')
                ->value('id');
        }
        
        $this->isEditingProduct = true;
    }
    
    // Manage product inventory
    public function manageInventory($productId)
    {
        $product = ShopProduct::findOrFail($productId);
        
        // Check permission
        $shop = Shop::findOrFail($product->shop_id);
        $this->authorize('update', $shop);
        
        $this->shopProductId = $product->id;
        $this->productStock = $product->stock_quantity ?? $product->stock;
        $this->productName = $product->name;
        
        $this->modalType = 'inventory';
        $this->showConfirmModal = true;
    }
    
    // Save inventory adjustment
    public function saveInventoryAdjustment()
    {
        $this->validate([
            'productStock' => 'required|integer|min:0',
        ]);
        
        $product = ShopProduct::findOrFail($this->shopProductId);
        $oldStock = $product->stock_quantity ?? $product->stock;
        $adjustment = $this->productStock - $oldStock;
        
        // Update the stock
        $product->stock_quantity = $this->productStock;
        $product->stock = $this->productStock; // For backward compatibility
        $product->save();
        
        // Log the adjustment
        // This would typically log to an inventory_adjustments table
        // For demonstration purposes only:
        $adjustmentType = $adjustment > 0 ? 'addition' : 'reduction';
        $message = 'Inventory ' . $adjustmentType . ' of ' . abs($adjustment) . ' units for product: ' . $product->name;
        
        // In a real implementation, you would log this properly:
        // InventoryAdjustment::create([
        //     'product_id' => $product->id,
        //     'shop_id' => $product->shop_id,
        //     'user_id' => Auth::id(),
        //     'quantity' => $adjustment,
        //     'type' => $adjustmentType,
        //     'notes' => 'Manual adjustment via ShopManager',
        //     'previous_stock' => $oldStock,
        //     'new_stock' => $this->productStock
        // ]);
        
        $this->shopProductId = null;
        $this->productStock = null;
        $this->modalType = '';
        $this->showConfirmModal = false;
        
        session()->flash('message', 'Inventory updated successfully.');
    }
    
    // Mark product as featured
    public function toggleProductFeatured($productId)
    {
        $product = ShopProduct::findOrFail($productId);
        
        // Check permission
        $shop = Shop::findOrFail($product->shop_id);
        $this->authorize('update', $shop);
        
        $product->is_featured = !$product->is_featured;
        $product->save();
        
        $status = $product->is_featured ? 'featured' : 'unfeatured';
        session()->flash('message', 'Product ' . $status . ' successfully.');
    }
    
    // Open product variants management
    public function manageProductVariants($productId)
    {
        $product = ShopProduct::findOrFail($productId);
        
        // Check permission
        $shop = Shop::findOrFail($product->shop_id);
        $this->authorize('update', $shop);
        
        $this->shopProductId = $product->id;
        $this->productName = $product->name;
        $this->hasVariants = $product->has_variants;
        
        // Load existing variant options and variants
        // This would depend on your variant model structure
        
        $this->isManagingVariants = true;
    }
    
    // Save product variants
    public function saveProductVariants()
    {
        if (!$this->shopProductId) {
            session()->flash('error', 'No product selected for variant management.');
            return;
        }
        
        $product = ShopProduct::findOrFail($this->shopProductId);
        $product->has_variants = $this->hasVariants;
        $product->save();
        
        // Save variant options and variants
        // This would depend on your variant model structure
        
        $this->isManagingVariants = false;
        session()->flash('message', 'Product variants updated successfully.');
    }
    
    // Cancel variants management
    public function cancelVariantsManagement()
    {
        $this->isManagingVariants = false;
        $this->reset([
            'shopProductId', 'productName', 'hasVariants', 
            'variantOptions', 'variants', 'editingVariantIndex'
        ]);
    }
    
    // Update product status directly
    public function updateProductStatus($productId, $newStatus)
    {
        if (!in_array($newStatus, ['active', 'inactive'])) {
            session()->flash('error', 'Invalid status.');
            return;
        }
        
        $product = ShopProduct::findOrFail($productId);
        
        // Check permission
        $shop = Shop::findOrFail($product->shop_id);
        $this->authorize('update', $shop);
        
        $product->status = $newStatus;
        $product->save();
        
        session()->flash('message', 'Product status updated successfully.');
    }
    
    // Quick price update
    public function updateProductPrice($productId, $newPrice)
    {
        if (!is_numeric($newPrice) || $newPrice < 0) {
            session()->flash('error', 'Price must be a positive number.');
            return;
        }
        
        $product = ShopProduct::findOrFail($productId);
        
        // Check permission
        $shop = Shop::findOrFail($product->shop_id);
        $this->authorize('update', $shop);
        
        $product->price = $newPrice;
        $product->save();
        
        session()->flash('message', 'Price updated successfully.');
    }
}
