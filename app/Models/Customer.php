<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'image',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'date_of_birth',
        'gender',
        'email_verified',
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

    /**
     * Relationship: Customer belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
