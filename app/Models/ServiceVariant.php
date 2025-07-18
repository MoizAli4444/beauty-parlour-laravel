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

    public function getStatusBadgeAttribute()
    {
        return render_status_badge($this->status, $this->id, route('services.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('services.edit', $this->slug));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('services.show', $this->slug));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('services.destroy', $this->id));
    }

    // {!! render_delete_button($service->id, route('services.destroy', $service->id)) !!}



}
