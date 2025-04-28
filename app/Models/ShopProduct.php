<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShopProduct extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
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
}
