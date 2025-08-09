<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BookingAddon extends Model
{
    // protected $fillable = ['booking_id', 'addon_id'];

    // public function booking()
    // {
    //     return $this->belongsTo(Booking::class);
    // }

    // public function addon()
    // {
    //     return $this->belongsTo(Addon::class);
    // }

    protected $fillable = ['booking_id', 'addon_id', 'price', 'staff_id', 'status'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
