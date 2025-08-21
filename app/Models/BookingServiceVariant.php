<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class BookingServiceVariant extends Model
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingServiceVariant extends Pivot
{

    protected $table = 'booking_service_variant';
    
    public $incrementing = true; // Optional: only if your pivot has an id column

    protected $fillable = ['booking_id', 'service_variant_id', 'price', 'staff_id', 'status'];

    // Automatically eager load staff when pivot is loaded
    protected $with = ['staff'];




    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function serviceVariant()
    {
        return $this->belongsTo(ServiceVariant::class);
    }

    

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id')->with('user');
    }
}
