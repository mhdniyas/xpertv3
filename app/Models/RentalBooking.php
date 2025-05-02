<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RentalBooking extends Model
{
    protected $fillable = [
        'booking_number',
        'shop_id',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'assigned_staff_id',
        'rental_products',
        'rental_start',
        'rental_end',
        'rental_type',
        'subtotal',
        'tax',
        'discount',
        'deposit',
        'damage_charges',
        'total_amount',
        'amount_paid',
        'balance',
        'payment_status',
        'booking_status',
        'picked_up_at',
        'returned_at',
        'created_by',
        'customer_priority_points',
    ];

    protected $casts = [
        'rental_products' => 'array',
        'rental_start' => 'datetime',
        'rental_end' => 'datetime',
        'picked_up_at' => 'datetime',
        'returned_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'deposit' => 'decimal:2',
        'damage_charges' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'customer_priority_points' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Generate a unique booking number when creating a new booking
        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }

            // Calculate balance if not explicitly set
            if (!isset($booking->balance)) {
                $booking->balance = $booking->total_amount - $booking->amount_paid;
            }
        });

        // Update balance after updating payment info
        static::updating(function ($booking) {
            if ($booking->isDirty(['total_amount', 'amount_paid'])) {
                $booking->balance = $booking->total_amount - $booking->amount_paid;
            }
        });
    }

    /**
     * Get the shop associated with the booking.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the customer associated with the booking.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the customer profile associated with the booking.
     */
    public function customerProfile()
    {
        return $this->belongsTo(RentalCustomerProfile::class, 'customer_id', 'user_id')
            ->where('shop_id', $this->shop_id);
    }

    /**
     * Get the staff member assigned to this booking.
     */
    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Get the user who created the booking.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all notes for this booking.
     */
    public function notes()
    {
        return $this->hasMany(RentalBookingNote::class);
    }

    /**
     * Get the products associated with this booking.
     */
    public function products()
    {
        return $this->belongsToMany(RentalProduct::class, 'rental_booking_products')
            ->withPivot('quantity', 'rate', 'rate_type', 'amount')
            ->withTimestamps();
    }

    /**
     * Get the duration of the rental in hours.
     */
    public function getDurationInHours()
    {
        return $this->rental_start->diffInHours($this->rental_end);
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDuration()
    {
        $days = $this->rental_start->diffInDays($this->rental_end);
        $hours = $this->rental_start->copy()->addDays($days)->diffInHours($this->rental_end);

        if ($days > 0) {
            return $days . ' day' . ($days > 1 ? 's' : '') . ($hours > 0 ? ', ' . $hours . ' hour' . ($hours > 1 ? 's' : '') : '');
        }

        return $hours . ' hour' . ($hours > 1 ? 's' : '');
    }

    /**
     * Process the pickup of a rental.
     */
    public function processPickup($staffId = null)
    {
        if ($this->booking_status !== 'confirmed') {
            return false;
        }

        $this->booking_status = 'picked-up';
        $this->picked_up_at = now();

        if ($staffId) {
            $this->assigned_staff_id = $staffId;
        }

        $this->save();

        // Update product availability
        $this->updateProductAvailability(-1); // Decrease available quantity

        return true;
    }

    /**
     * Process the return of a rental.
     */
    public function processReturn($damageCharges = 0, $additionalPayment = 0, $staffId = null)
    {
        if ($this->booking_status !== 'picked-up') {
            return false;
        }

        $this->booking_status = 'returned';
        $this->returned_at = now();

        if ($damageCharges > 0) {
            $this->damage_charges = $damageCharges;
            $this->total_amount += $damageCharges;
        }

        if ($additionalPayment > 0) {
            $this->amount_paid += $additionalPayment;
        }

        $this->balance = $this->total_amount - $this->amount_paid;

        if ($this->balance <= 0) {
            $this->payment_status = 'paid';
        }

        if ($staffId) {
            $this->assigned_staff_id = $staffId;
        }

        $this->save();

        // Update product availability
        $this->updateProductAvailability(1); // Increase available quantity

        // Update customer priority points
        $this->updateCustomerPoints();

        return true;
    }

    /**
     * Cancel the booking.
     */
    public function cancel($refundAmount = 0)
    {
        if ($this->booking_status === 'picked-up' || $this->booking_status === 'returned') {
            return false;
        }

        $this->booking_status = 'canceled';

        if ($refundAmount > 0 && $this->amount_paid >= $refundAmount) {
            $this->amount_paid -= $refundAmount;
            $this->balance = $this->total_amount - $this->amount_paid;
            $this->payment_status = 'refunded';
        }

        $this->save();

        return true;
    }

    /**
     * Update the availability of the products in this booking.
     */
    private function updateProductAvailability($direction)
    {
        foreach ($this->products as $product) {
            $quantity = $product->pivot->quantity;
            $product->updateAvailableQuantity($quantity * $direction);
        }
    }

    /**
     * Update customer priority points.
     */
    private function updateCustomerPoints()
    {
        if (!$this->customer_id) {
            return;
        }

        // Find or create customer profile
        $profile = RentalCustomerProfile::firstOrCreate([
            'shop_id' => $this->shop_id,
            'user_id' => $this->customer_id
        ], [
            'name' => $this->customer_name,
            'email' => $this->customer_email,
            'phone' => $this->customer_phone,
            'address' => $this->customer_address
        ]);

        // Calculate points based on total amount (1 point per $10)
        $earnedPoints = floor($this->total_amount / 10);

        // Update profile
        $profile->total_rentals += 1;
        $profile->priority_points += $earnedPoints;
        $profile->total_spent += $this->total_amount;
        $profile->last_rental_date = now();

        // Update tier based on points
        if ($profile->priority_points >= 500) {
            $profile->customer_tier = 'platinum';
        } elseif ($profile->priority_points >= 250) {
            $profile->customer_tier = 'gold';
        } elseif ($profile->priority_points >= 100) {
            $profile->customer_tier = 'silver';
        } elseif ($profile->priority_points >= 50) {
            $profile->customer_tier = 'bronze';
        }

        $profile->save();

        // Also update the booking with points earned
        $this->customer_priority_points = $earnedPoints;
        $this->save();
    }

    /**
     * Scope a query to only include bookings for a specific shop.
     */
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    /**
     * Scope a query to only include active bookings (confirmed or picked-up).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('booking_status', ['confirmed', 'picked-up']);
    }

    /**
     * Scope a query to only include bookings with overdue returns.
     */
    public function scopeOverdue($query)
    {
        return $query->where('booking_status', 'picked-up')
            ->where('rental_end', '<', now());
    }
}
