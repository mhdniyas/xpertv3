<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShopProductManager extends Component
{
    use WithPagination, WithFileUploads, AuthorizesRequests;
    
    public $shop;
    public $showModal = false;
    public $showImportModal = false;
    public $editMode = false;
    
    // Product attributes
    public $productId;
    public $name;
    public $shop_category_id;
    public $price;
    public $unit;
    public $stock_quantity = 0;
    public $image;
    public $existingImage;
    public $status = 'active';
    
    // Importing global products
    public $globalProductId;
    public $importPrice;
    public $importStock;
    
    // Filters
    public $searchTerm = '';
    public $categoryFilter = '';
    public $sourceTypeFilter = '';
    public $perPage = 10;
    
    protected $rules = [
        'name' => 'required|min:3|max:255',
        'shop_category_id' => 'required|exists:shop_categories,id',
        'price' => 'required|numeric|min:0',
        'unit' => 'nullable|string|max:50',
        'stock_quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|max:1024', // 1MB max
        'status' => 'required|in:active,inactive',
    ];
    
    public function mount(Shop $shop)
    {
        $this->shop = $shop;
        
        // Authorize that the current user can manage products for this shop
        $this->authorize('manageProducts', $shop);
    }
    
    public function render()
    {
        $query = ShopProduct::where('shop_id', $this->shop->id)
            ->when($this->searchTerm, function($query) {
                return $query->where('name', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->categoryFilter, function($query) {
                return $query->where('shop_category_id', $this->categoryFilter);
            })
            ->when($this->sourceTypeFilter, function($query) {
                return $query->where('source_type', $this->sourceTypeFilter);
            });
            
        $products = $query->orderBy('name')->paginate($this->perPage);
        
        $categories = ShopCategory::where('shop_id', $this->shop->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        $globalProducts = Product::where('is_global', true)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('livewire.shop-product-manager', [
            'products' => $products,
            'categories' => $categories,
            'globalProducts' => $globalProducts,
        ]);
    }
    
    public function openModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function openImportModal()
    {
        $this->resetValidation();
        $this->globalProductId = null;
        $this->importPrice = null;
        $this->importStock = 0;
        $this->showImportModal = true;
    }
    
    public function closeImportModal()
    {
        $this->showImportModal = false;
    }
    
    public function resetForm()
    {
        $this->editMode = false;
        $this->productId = null;
        $this->name = '';
        $this->shop_category_id = '';
        $this->price = '';
        $this->unit = '';
        $this->stock_quantity = 0;
        $this->image = null;
        $this->existingImage = null;
        $this->status = 'active';
    }
    
    public function edit(ShopProduct $product)
    {
        $this->authorize('update', $product);
        
        $this->resetValidation();
        $this->editMode = true;
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->shop_category_id = $product->shop_category_id;
        $this->price = $product->price;
        $this->unit = $product->unit;
        $this->stock_quantity = $product->stock_quantity;
        $this->existingImage = $product->image;
        $this->status = $product->status;
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate();
        
        // Handle image upload
        $imagePath = $this->existingImage;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }
        
        if ($this->editMode) {
            $product = ShopProduct::findOrFail($this->productId);
            $this->authorize('update', $product);
            
            $product->update([
                'name' => $this->name,
                'shop_category_id' => $this->shop_category_id,
                'price' => $this->price,
                'unit' => $this->unit,
                'stock_quantity' => $this->stock_quantity,
                'image' => $imagePath,
                'status' => $this->status,
            ]);
            
            $this->dispatch('toast', 'Product updated successfully!', 'success');
        } else {
            // Create new local product
            ShopProduct::create([
                'shop_id' => $this->shop->id,
                'name' => $this->name,
                'shop_category_id' => $this->shop_category_id,
                'price' => $this->price,
                'unit' => $this->unit,
                'stock_quantity' => $this->stock_quantity,
                'image' => $imagePath,
                'source_type' => 'local',
                'slug' => Str::slug($this->name),
                'status' => $this->status,
                'created_by' => auth()->id(),
            ]);
            
            $this->dispatch('toast', 'Product created successfully!', 'success');
        }
        
        $this->closeModal();
    }
    
    public function importGlobalProduct()
    {
        $this->validate([
            'globalProductId' => 'required|exists:products,id',
            'importPrice' => 'required|numeric|min:0',
            'importStock' => 'required|integer|min:0',
            'shop_category_id' => 'required|exists:shop_categories,id',
        ]);
        
        $globalProduct = Product::findOrFail($this->globalProductId);
        
        // Check if product already exists in this shop
        $existingProduct = ShopProduct::where('shop_id', $this->shop->id)
            ->where('global_product_id', $globalProduct->id)
            ->first();
            
        if ($existingProduct) {
            $this->dispatch('toast', 'This global product is already imported to your shop.', 'error');
            return;
        }
        
        // Import the global product
        ShopProduct::create([
            'shop_id' => $this->shop->id,
            'name' => $globalProduct->name,
            'shop_category_id' => $this->shop_category_id,
            'price' => $this->importPrice,
            'unit' => $globalProduct->unit ?? '',
            'stock_quantity' => $this->importStock,
            'image' => $globalProduct->image,
            'source_type' => 'global',
            'global_product_id' => $globalProduct->id,
            'slug' => Str::slug($globalProduct->name),
            'status' => 'active',
            'created_by' => auth()->id(),
        ]);
        
        $this->dispatch('toast', 'Global product imported successfully!', 'success');
        $this->closeImportModal();
    }
    
    public function delete(ShopProduct $product)
    {
        $this->authorize('delete', $product);
        
        $product->delete();
        $this->dispatch('toast', 'Product deleted successfully!', 'success');
    }
    
    public function toggleStatus(ShopProduct $product)
    {
        $this->authorize('update', $product);
        
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);
        
        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
        $this->dispatch('toast', "Product {$statusText} successfully!", 'success');
    }
    
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }
    
    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }
    
    public function updatedSourceTypeFilter()
    {
        $this->resetPage();
    }
}
