<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingServiceVariant extends Model
{

    protected $table = 'booking_service_variant';

    protected $fillable = ['booking_id', 'service_variant_id', 'price', 'staff_id', 'status'];

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
        return $this->belongsTo(User::class, 'staff_id');
    }
}
