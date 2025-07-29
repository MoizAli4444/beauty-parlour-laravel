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
        'status' => 'string',
        'gender' => GenderType::class,
    ];

    // $user->gender === GenderType::Female

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'price',
        'duration_minutes',
        'status',
        'gender',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusBadgeAttribute()
    {
        return render_status_badge($this->status, $this->id, route('addons.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('addons.edit', $this->slug));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('addons.show', $this->slug));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('addons.destroy', $this->id));
    }

    // {!! render_delete_button($addon->id, route('addons.destroy', $addon->id)) !!}

    public function getGenderBadgeAttribute()
    {
        return render_gender_badge($this->gender);
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
