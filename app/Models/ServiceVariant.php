<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ServiceVariant extends Model
{

    use Sluggable;

    protected $fillable = [
        'service_id',
        'image',
        'name',
        'slug',
        'description',
        'price',
        'duration',
        'status',
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

    public function service()
    {
        return $this->belongsTo(Service::class);
    }


    public function getStatusBadgeAttribute()
    {
        return render_status_badge($this->status, $this->id, route('service-variants.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('service-variants.edit', $this->slug));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('service-variants.show', $this->slug));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('service-variants.destroy', $this->id));
    }

    // {!! render_delete_button($service->id, route('service-variants.destroy', $service->id)) !!}

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    //////////////////////////////////////
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service_variant')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    
    
}
