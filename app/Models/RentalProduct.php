<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RentalProduct extends Model
{
    protected $fillable = [
        'shop_id',
        'shop_category_id',
        'name',
        'slug',
        'description',
        'sku',
        'barcode',
        'stock_quantity',
        'available_quantity',
        'unit',
        'status',
        'condition',
        'hourly_rate',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'deposit_amount',
        'maintenance_history',
        'images',
        'primary_image',
        'is_combo',
        'combo_products',
        'created_by',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'available_quantity' => 'integer',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'weekly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'maintenance_history' => 'array',
        'images' => 'array',
        'is_combo' => 'boolean',
        'combo_products' => 'array',
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

            // Ensure available_quantity defaults to stock_quantity if not set
            if (!isset($product->available_quantity)) {
                $product->available_quantity = $product->stock_quantity;
            }
        });
    }

    /**
     * Get the shop that owns this rental product.
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
     * Get the user who created this product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the bookings where this product was rented.
     */
    public function bookings()
    {
        return $this->belongsToMany(RentalBooking::class, 'rental_booking_products')
            ->withPivot('quantity', 'rate', 'rate_type', 'amount')
            ->withTimestamps();
    }

    /**
     * Check if the product is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the product is in maintenance.
     */
    public function isInMaintenance()
    {
        return $this->status === 'maintenance';
    }

    /**
     * Check if the product is a combo product.
     */
    public function isCombo()
    {
        return $this->is_combo;
    }

    /**
     * Get the component products if this is a combo product.
     */
    public function getComboProducts()
    {
        if (!$this->is_combo || empty($this->combo_products)) {
            return collect();
        }

        $productIds = $this->combo_products;
        return self::whereIn('id', $productIds)->get();
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include products with available stock.
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    /**
     * Scope a query to only include products for a specific shop.
     */
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    /**
     * Calculate best rate based on rental duration (in hours).
     */
    public function getBestRate($hours)
    {
        $rates = [
            'hourly' => $this->hourly_rate * $hours,
            'daily' => $this->daily_rate * ceil($hours / 24),
            'weekly' => $this->weekly_rate * ceil($hours / (24 * 7)),
            'monthly' => $this->monthly_rate * ceil($hours / (24 * 30)),
        ];

        // Filter out null rates
        $validRates = array_filter($rates, function ($rate) {
            return !is_null($rate);
        });

        if (empty($validRates)) {
            return null;
        }

        return min($validRates);
    }

    /**
     * Update available quantity after a booking or return.
     */
    public function updateAvailableQuantity($change)
    {
        $this->available_quantity += $change;

        // Ensure available quantity doesn't exceed stock quantity
        if ($this->available_quantity > $this->stock_quantity) {
            $this->available_quantity = $this->stock_quantity;
        }

        // Ensure available quantity doesn't go negative
        if ($this->available_quantity < 0) {
            $this->available_quantity = 0;
        }

        $this->save();

        return $this->available_quantity;
    }

    /**
     * Record maintenance for the product.
     */
    public function recordMaintenance($description, $cost = 0, $performedBy = null)
    {
        $history = $this->maintenance_history ?? [];

        $history[] = [
            'date' => now()->toDateTimeString(),
            'description' => $description,
            'cost' => $cost,
            'performed_by' => $performedBy,
        ];

        $this->maintenance_history = $history;
        $this->save();

        return $this;
    }
}
