<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Cviebrock\EloquentSluggable\Sluggable;

class Staff extends Model
{
    // use Sluggable;

    protected $fillable = [
        'user_id',
        'staff_role_id',
        'phone',
        'address',
        'date_of_birth',
        'joining_date',
        'leaving_date',
        'is_head',
        'cnic',
        'emergency_contact',
        'image',
        'shift_id',
        'working_days',
        'salary',
        'payment_schedule',
        'payment_method_id',
        'bank_account_number',
        'is_verified',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'leaving_date' => 'date',
        'is_head' => 'boolean',
        'is_verified' => 'boolean',
        'working_days' => 'array',
        'salary' => 'decimal:2',
    ];

    // public function sluggable(): array
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'name',
    //             'onUpdate' => true
    //         ]
    //     ];
    // }

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
        return render_status_badge($this->status, $this->id, route('staff.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(
            $this->user?->slug ? route('staff.edit', $this->user->slug) : '#'
        );
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(
            $this->user?->slug ? route('staff.show', $this->user->slug) : '#'
        );
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('staff.destroy', $this->id));
    }

    // {!! render_delete_button($service->id, route('staff.destroy', $service->id)) !!}

    /**
     * Relationship: Staff belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staffRole()
    {
        return $this->belongsTo(StaffRole::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
