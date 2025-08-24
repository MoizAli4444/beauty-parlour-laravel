<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'customer_id',

        'offer_id',
        'appointment_time',
        'discount',
        'addon_amount',
        'service_variant_amount',
        'subtotal',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'note',
        'cancel_reason', //
        'created_by', //
        'updated_by', //
    ];


    protected $casts = [
        'appointment_time' => 'datetime',
        // 'status' => 'integer',
        'payment_status' => 'integer',
        'service_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'addon_amount' => 'decimal:2',
        'tip_amount' => 'decimal:2',
        'payable_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'deleted_at' => 'datetime', // handled by SoftDeletes
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'rejected' => 'Rejected',
    ];

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending'     => 'badge bg-warning',
            'confirmed'   => 'badge bg-primary',
            'in_progress' => 'badge bg-info',
            'completed'   => 'badge bg-success',
            'cancelled'   => 'badge bg-secondary',
            'rejected'    => 'badge bg-danger',
        ];

        $color = $colors[$this->status] ?? 'badge bg-dark';
        return '<span class="' . $color . '">' . ucwords(str_replace('_', ' ', $this->status)) . '</span>';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $colors = [
            0 => 'badge bg-danger',   // Unpaid
            1 => 'badge bg-success',  // Paid
            2 => 'badge bg-warning',  // Refunded
        ];

        $statuses = [
            0 => 'Unpaid',
            1 => 'Paid',
            2 => 'Refunded',
        ];

         $status = $statuses[$this->payment_status] ?? 'Unknown';
        //  dd($status);

        $color = $colors[$this->payment_status] ?? 'badge bg-dark';
        // return '<span class="' . $color . '">' . ucwords(str_replace('_', ' ', $this->payment_status)) . '</span>';
        return '<span class="' . $color . '">' . ucwords(str_replace('_', ' ', $status)) . '</span>';
    }

    public function getPaymentStatusBadgesAttribute()
    {
        $statuses = [
            0 => 'Unpaid',
            1 => 'Paid',
            2 => 'Refunded',
        ];

        $colors = [
            0 => 'badge bg-danger',   // Unpaid
            1 => 'badge bg-success',  // Paid
            2 => 'badge bg-warning',  // Refunded
        ];

        $status = $statuses[$this->payment_status] ?? 'Unknown';
        $color  = $colors[$this->payment_status] ?? 'badge bg-dark';

        return '<span class="' . $color . '">' . $status . '</span>';
    }





    ///////////// fixed model functions //////////////
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

    // public function getStatusBadgeAttribute()
    // {
    //     return render_status_badge($this->status, $this->id, route('bookings.toggle-status', $this->id));
    // }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('bookings.edit', $this->id));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('bookings.show', $this->id));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('bookings.destroy', $this->id));
    }

    // {!! render_delete_button($service->id, route('bookings.destroy', $service->id)) !!}
    ///////////// fixed model functions //////////////



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }


    /////////
    // Relationship with booking_addons table (pivot + extra fields)
    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')
            ->using(BookingAddon::class)
            ->withPivot('price', 'staff_id', 'status')
            ->withTimestamps();
    }


    // Relationship with booking_service_variant table (pivot + extra fields)
    public function bookingServiceVariants()
    {
        // return $this->hasMany(BookingServiceVariant::class);
        return $this->hasMany(ServiceVariant::class);
    }

    // Access related service variants (many-to-many with pivot data)
    public function serviceVariants()
    {
        return $this->belongsToMany(ServiceVariant::class, 'booking_service_variant', 'booking_id', 'service_variant_id')
            ->using(BookingServiceVariant::class)
            ->withPivot('price', 'staff_id', 'status')
            ->withTimestamps();
    }
}
