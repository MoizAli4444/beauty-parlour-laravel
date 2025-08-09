<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id',

        'offer_id',
        'appointment_time',
        'discount',
        'addon_amount',
        'service_variant_amount',
        'subtotal',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'note',
        'cancel_reason', //
        'created_by', //
        'updated_by', //
    ];


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


    // public function bookingAddons()
    // {
    //     return $this->hasMany(BookingAddon::class);
    // }

    // public function addons()
    // {
    //     return $this->belongsToMany(Addon::class, 'booking_addons')->withTimestamps();
    // }


    // public function serviceVariants()
    // {
    //     return $this->belongsToMany(ServiceVariant::class, 'booking_service_variant')
    //         ->withPivot('price')
    //         ->withTimestamps();
    // }

    /////////
    // Relationship with booking_addons table (pivot + extra fields)
    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    // Access related addons (many-to-many through pivot table with extra fields like price, staff_id, status)
    // public function addons()
    // {
    //     return $this->belongsToMany(Addon::class, 'booking_addons')
    //         ->withPivot('price', 'staff_id', 'status')
    //         ->withTimestamps();
    // }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')
            ->withPivot('price', 'staff_id', 'status')
            ->withTimestamps();
    }


    // Relationship with booking_service_variant table (pivot + extra fields)
    public function bookingServiceVariants()
    {
        // return $this->hasMany(BookingServiceVariant::class);
        return $this->hasMany(ServiceVariant::class);
    }

    // Access related service variants (many-to-many with pivot data)
    public function serviceVariants()
    {
        return $this->belongsToMany(ServiceVariant::class, 'booking_service_variant', 'booking_id', 'service_variant_id')
            ->withPivot('price', 'staff_id', 'status')
            ->withTimestamps();
    }

    /////////
}
