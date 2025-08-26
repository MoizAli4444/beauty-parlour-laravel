<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'rating',
        'review',
        'status',
        'moderator_id',
        'moderator_type',
    ];

    // Review belongs to a booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Review belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Polymorphic moderator (can be Admin or Staff)
    public function moderator()
    {
        return $this->morphTo();
    }
}
