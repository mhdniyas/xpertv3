<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Category;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'owner_id',
        'status',
        'rejection_reason',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            if (empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name);
            }
        });
    }

    /**
     * Get the owner of the shop.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the staff members assigned to this shop.
     */
    public function staff()
    {
        return $this->belongsToMany(User::class, 'shop_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all products for this shop.
     */
    public function products()
    {
        return $this->hasMany(ShopProduct::class);
    }

    /**
     * Get all categories for this shop.
     */
    public function shopCategories()
    {
        return $this->hasMany(ShopCategory::class);
    }

    /**
     * Get all categories associated with this shop through shop categories.
     */
    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            ShopCategory::class,
            'shop_id', // Foreign key on ShopCategory table
            'id', // Foreign key on Category table (referenced by category_id on ShopCategory)
            'id', // Local key on Shop table
            'category_id' // Local key on ShopCategory table
        );
    }

    /**
     * Check if the shop is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the shop is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the shop is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope a query to only include active shops.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter shops by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
