<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalCustomerProfile extends Model
{
    protected $fillable = [
        'shop_id',
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'id_type',
        'id_number',
        'total_rentals',
        'priority_points',
        'customer_tier',
        'total_spent',
        'rental_history',
        'last_rental_date',
    ];

    protected $casts = [
        'total_rentals' => 'integer',
        'priority_points' => 'integer',
        'total_spent' => 'decimal:2',
        'rental_history' => 'array',
        'last_rental_date' => 'datetime',
    ];

    /**
     * Get the shop that this customer profile belongs to.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the user account associated with this customer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings made by this customer at this shop.
     */
    public function bookings()
    {
        return $this->hasMany(RentalBooking::class, 'customer_id', 'user_id')
            ->where('shop_id', $this->shop_id);
    }

    /**
     * Get the active bookings for this customer.
     */
    public function activeBookings()
    {
        return $this->bookings()
            ->whereIn('booking_status', ['confirmed', 'picked-up']);
    }

    /**
     * Check if this customer is a registered user or just a guest profile.
     */
    public function isRegisteredUser()
    {
        return !is_null($this->user_id);
    }

    /**
     * Get tier-based discount percentage.
     */
    public function getTierDiscount()
    {
        switch ($this->customer_tier) {
            case 'platinum':
                return 15; // 15% discount
            case 'gold':
                return 10; // 10% discount
            case 'silver':
                return 5; // 5% discount
            case 'bronze':
                return 2; // 2% discount
            default:
                return 0; // No discount
        }
    }

    /**
     * Record a rental in the customer's history.
     */
    public function recordRental($booking)
    {
        $history = $this->rental_history ?? [];

        $history[] = [
            'booking_id' => $booking->id,
            'booking_number' => $booking->booking_number,
            'date' => $booking->created_at->toDateTimeString(),
            'total_amount' => $booking->total_amount,
            'status' => $booking->booking_status,
        ];

        $this->rental_history = $history;
        $this->last_rental_date = now();
        $this->total_rentals += 1;
        $this->save();

        return $this;
    }

    /**
     * Update customer points based on a booking.
     */
    public function addPoints($points, $amount = null)
    {
        $this->priority_points += $points;

        if ($amount) {
            $this->total_spent += $amount;
        }

        // Update tier based on total points
        if ($this->priority_points >= 500) {
            $this->customer_tier = 'platinum';
        } elseif ($this->priority_points >= 250) {
            $this->customer_tier = 'gold';
        } elseif ($this->priority_points >= 100) {
            $this->customer_tier = 'silver';
        } elseif ($this->priority_points >= 50) {
            $this->customer_tier = 'bronze';
        }

        $this->save();

        return $this;
    }

    /**
     * Scope a query to only include customers of a specific tier.
     */
    public function scopeByTier($query, $tier)
    {
        return $query->where('customer_tier', $tier);
    }

    /**
     * Scope a query to only include VIP customers (gold or platinum).
     */
    public function scopeVip($query)
    {
        return $query->whereIn('customer_tier', ['gold', 'platinum']);
    }

    /**
     * Scope a query to only include customers for a specific shop.
     */
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }
}
