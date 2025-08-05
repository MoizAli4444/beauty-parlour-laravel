<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{

    use SoftDeletes;

    protected $casts = [
        'appointment_time' => 'datetime',
        'status' => 'integer',
        'payment_status' => 'integer',
        'service_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'addon_amount' => 'decimal:2',
        'tip_amount' => 'decimal:2',
        'payable_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'deleted_at' => 'datetime', // handled by SoftDeletes
    ];


    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')->withTimestamps();
    }

    public function serviceVariants()
    {
        return $this->belongsToMany(ServiceVariant::class, 'booking_service_variant')
            ->withPivot('price')
            ->withTimestamps();
    }
}
