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
        'gender' => GenderType::class,
    ];

    // $user->gender === GenderType::Female


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
