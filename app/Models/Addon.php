<?php

namespace App\Models;

use App\GenderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Addon extends Model
{

    use Sluggable;
    use SoftDeletes;

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'integer',
        'gender' => GenderType::class,
    ];

    // $user->gender === GenderType::Female

    protected $fillable = [
        'name',
        'description',
        'price',
        'status', // e.g., 0 = inactive, 1 = active
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }


    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_addons')->withTimestamps();
    }
}
