<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $casts = [
    'appointment_time' => 'datetime',
    'status' => 'integer',
    'payment_status' => 'integer',
    'is_rescheduled' => 'boolean',
    'total_amount' => 'decimal:2',
    'completed_at' => 'datetime',
];

}
