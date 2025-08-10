<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return render_status_badge($this->status, $this->id, route('customers.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(
            $this->user?->slug ? route('customers.edit', $this->user->slug) : '#'
        );
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(
            $this->user?->slug ? route('customers.show', $this->user->slug) : '#'
        );
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('customers.destroy', $this->id));
    }

    // {!! render_delete_button($service->id, route('customers.destroy', $service->id)) !!}

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Relationship: Customer belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
