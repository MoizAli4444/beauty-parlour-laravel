<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'rating',
        'review',
        'status',
        'moderator_id',
        'moderator_type',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending'     => 'badge bg-info',
            'approved'   => 'badge bg-success',
            'rejected'    => 'badge bg-danger',
        ];

        $color = $colors[$this->status] ?? 'badge bg-dark';
        return '<span class="' . $color . '">' . ucwords(str_replace('_', ' ', $this->status)) . '</span>';
    }

    public function getChangeStatusButtonAttribute()
    {
        return '<button class="btn btn-sm btn-outline-primary change-status-btn text-nowrap" 
                data-id="' . $this->id . '" 
                data-status="' . $this->status . '">
                Change Status
            </button>';
    }


    // Review belongs to a booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Review belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Polymorphic moderator (can be Admin or Staff)
    public function moderator()
    {
        return $this->morphTo();
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('booking-reviews.show', $this->id));
    }
}
