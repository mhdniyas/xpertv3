<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalBookingNote extends Model
{
    protected $fillable = [
        'rental_booking_id',
        'user_id',
        'note_type',
        'note',
        'images',
        'product_conditions',
        'is_internal',
    ];

    protected $casts = [
        'images' => 'array',
        'product_conditions' => 'array',
        'is_internal' => 'boolean',
    ];

    /**
     * Get the booking that this note belongs to.
     */
    public function booking()
    {
        return $this->belongsTo(RentalBooking::class, 'rental_booking_id');
    }

    /**
     * Get the user who created this note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include internal notes.
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Scope a query to only include customer-facing notes.
     */
    public function scopeCustomerFacing($query)
    {
        return $query->where('is_internal', false);
    }

    /**
     * Scope a query to filter notes by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('note_type', $type);
    }

    /**
     * Check if the note has any images.
     */
    public function hasImages()
    {
        return !empty($this->images) && count($this->images) > 0;
    }

    /**
     * Get all notes related to damage.
     */
    public static function getDamageNotes($bookingId)
    {
        return self::where('rental_booking_id', $bookingId)
            ->where('note_type', 'damage')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all notes related to product condition.
     */
    public static function getConditionNotes($bookingId)
    {
        return self::where('rental_booking_id', $bookingId)
            ->whereIn('note_type', ['pickup', 'return'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
