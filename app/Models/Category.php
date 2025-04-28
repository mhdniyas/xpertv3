<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
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

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all recursive children categories.
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Get all active children.
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Get only parent categories.
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
