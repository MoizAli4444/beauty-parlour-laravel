<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingReview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'user_id',
        'rating',
        'review',
        'status',
    ];

    // A review belongs to a booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // A review belongs to a user (customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
