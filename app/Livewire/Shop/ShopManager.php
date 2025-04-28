<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    protected $listeners = [
        'selectShopForStaffAssignment', 
        'selectShopForCategoryManagement',
        'selectShopForProductManagement',
        'refreshShopList' => '$refresh'
    ];

    protected function rules()
    {
        return [
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
        ];
    }

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
                
                // Get shop products
                $shopProducts = ShopProduct::where('shop_id', $this->selectedShopId)
                    ->with('shopCategory')
                    ->get();
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
            'roles' => ['manager', 'staff'],
            'currentUser' => $currentUser
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
    
    // Open product edit modal
    public function editProduct($id = null)
    {
        $this->resetValidation();
        $this->reset([
            'shopProductId', 'productName', 'productDescription', 'productPrice',
            'productStock', 'productCategoryId', 'productImage', 'existingProductImage'
        ]);

        if ($id) {
            $product = ShopProduct::findOrFail($id);
            
            // Check permission to edit
            $shop = Shop::findOrFail($product->shop_id);
            $this->authorize('update', $shop);
            
            $this->shopProductId = $product->id;
            $this->productName = $product->name;
            $this->productDescription = $product->description;
            $this->productPrice = $product->price;
            $this->productStock = $product->stock;
            $this->productCategoryId = $product->shop_category_id;
            $this->existingProductImage = $product->image;
        }
        
        $this->isEditingProduct = true;
    }
    
    // Save product
    public function saveProduct()
    {
        $this->validate([
            'productName' => 'required|string|min:3|max:255',
            'productDescription' => 'nullable|string',
            'productPrice' => 'required|numeric|min:0',
            'productStock' => 'nullable|integer|min:0',
            'productCategoryId' => 'required|integer|exists:shop_categories,id',
            'productImage' => 'nullable|image|max:1024',
        ]);
        
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
        
        $product->name = $this->productName;
        $product->slug = Str::slug($this->productName);
        $product->description = $this->productDescription;
        $product->price = $this->productPrice;
        $product->stock = $this->productStock;
        $product->shop_category_id = $this->productCategoryId;
        
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
        
        $this->reset([
            'shopProductId', 'productName', 'productDescription', 'productPrice',
            'productStock', 'productCategoryId', 'productImage', 'existingProductImage'
        ]);
        $this->isEditingProduct = false;
        session()->flash('message', $this->shopProductId ? 'Product updated successfully.' : 'Product created successfully.');
    }
    
    // Cancel product editing
    public function cancelProductEdit()
    {
        $this->reset([
            'shopProductId', 'productName', 'productDescription', 'productPrice',
            'productStock', 'productCategoryId', 'productImage', 'existingProductImage'
        ]);
        $this->isEditingProduct = false;
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
}
