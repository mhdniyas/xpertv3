<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShopCategory extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'slug',
        'parent_id',
        'status',
        'created_by',
        'category_id',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the shop that owns this category.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the general category associated with this shop category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(ShopCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(ShopCategory::class, 'parent_id');
    }

    /**
     * Get all recursive children categories.
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Get the user who created this category.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get products in this category.
     */
    public function products()
    {
        return $this->hasMany(ShopProduct::class);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include parent categories (no parent).
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include categories of a specific shop.
     */
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }
}
