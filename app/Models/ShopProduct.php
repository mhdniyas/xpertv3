<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShopProduct extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'description', // Added description field
        'shop_category_id',
        'price',
        'unit',
        'stock_quantity',
        'image',
        'source_type',
        'global_product_id',
        'slug',
        'status',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Get the shop that owns this product.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the shop category that this product belongs to.
     */
    public function shopCategory()
    {
        return $this->belongsTo(ShopCategory::class);
    }

    /**
     * Get the global product that this shop product is based on (if any).
     */
    public function globalProduct()
    {
        return $this->belongsTo(Product::class, 'global_product_id');
    }

    /**
     * Get the user who created this product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if the product is from global catalog.
     */
    public function isGlobal()
    {
        return $this->source_type === 'global';
    }

    /**
     * Check if the product is locally created.
     */
    public function isLocal()
    {
        return $this->source_type === 'local';
    }

    /**
     * Check if the product is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include products of a specific shop.
     */
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    /**
     * Scope a query to only include local products.
     */
    public function scopeLocal($query)
    {
        return $query->where('source_type', 'local');
    }

    /**
     * Scope a query to only include products cloned from global catalog.
     */
    public function scopeGlobalSource($query)
    {
        return $query->where('source_type', 'global');
    }

    /**
     * Suggest this product for the global catalog
     */
    public function suggestForGlobalCatalog($makeGlobal = true)
    {
        // If already a global product, do nothing
        if ($this->isGlobal()) {
            return false;
        }

        // Otherwise, create a suggestion in the global Product table
        $suggestion = new Product();
        $suggestion->name = $this->name;
        $suggestion->description = $this->description;
        $suggestion->price = $this->price;
        $suggestion->stock = 0; // Global products start with 0 stock
        $suggestion->is_global = true;
        $suggestion->is_active = false; // Not active until approved
        $suggestion->status = 'pending';
        $suggestion->global_suggestion = true;
        $suggestion->slug = $this->slug;
        
        // Link to the shop that suggested it
        $suggestion->shop_id = $this->shop_id;
        
        // If we have a category_id from the global catalog, use it
        if ($this->shopCategory && $this->shopCategory->category_id) {
            $suggestion->category_id = $this->shopCategory->category_id;
        }
        
        $suggestion->save();
        
        // Update this shop product to link to the global product suggestion
        $this->global_suggestion_id = $suggestion->id;
        $this->save();
        
        return true;
    }
    
    /**
     * Check if this product has been suggested for the global catalog
     */
    public function isSuggestedForGlobal()
    {
        return !empty($this->global_suggestion_id);
    }
    
    /**
     * Get the suggested global product if it exists
     */
    public function getSuggestedGlobalProduct()
    {
        if ($this->global_suggestion_id) {
            return Product::find($this->global_suggestion_id);
        }
        return null;
    }
    
    /**
     * Approve this product for the global catalog
     * Should only be called by administrators
     */
    public function approveForGlobalCatalog()
    {
        // Find the suggested global product
        $globalProduct = $this->getSuggestedGlobalProduct();
        
        if (!$globalProduct) {
            return false;
        }
        
        // Update the status to approved
        $globalProduct->status = 'approved';
        $globalProduct->is_active = true;
        $globalProduct->global_suggestion = false; // No longer just a suggestion
        $globalProduct->save();
        
        // Update this shop product to link to the now-approved global product
        $this->global_product_id = $globalProduct->id;
        $this->source_type = 'global';
        $this->save();
        
        return true;
    }
    
    /**
     * Reject this product for the global catalog
     * Should only be called by administrators
     */
    public function rejectForGlobalCatalog($reason = '')
    {
        // Find the suggested global product
        $globalProduct = $this->getSuggestedGlobalProduct();
        
        if (!$globalProduct) {
            return false;
        }
        
        // Update the status to rejected
        $globalProduct->status = 'rejected';
        $globalProduct->rejection_reason = $reason;
        $globalProduct->save();
        
        return true;
    }
    
    /**
     * Check if this product has been approved for global catalog
     */
    public function isApprovedForGlobal()
    {
        $globalProduct = $this->getSuggestedGlobalProduct();
        return $globalProduct && $globalProduct->isApproved();
    }
    
    /**
     * Check if this product has been rejected for global catalog
     */
    public function isRejectedForGlobal()
    {
        $globalProduct = $this->getSuggestedGlobalProduct();
        return $globalProduct && $globalProduct->isRejected();
    }
}
