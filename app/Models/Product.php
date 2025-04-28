<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category_id',
        'shop_id',
        'is_global',
        'is_active',
        'status',
        'rejection_reason',
        'global_suggestion',
    ];

    protected $casts = [
        'is_global' => 'boolean',
        'is_active' => 'boolean',
        'global_suggestion' => 'boolean',
        'price' => 'decimal:2',
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
     * Get the category that the product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the shop that the product belongs to.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Check if the product is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the product is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the product is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope a query to only include global products.
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include products with global suggestion.
     */
    public function scopeGlobalSuggestion($query)
    {
        return $query->where('global_suggestion', true);
    }

    /**
     * Scope a query to filter products by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
