<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use Sluggable, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'value',
        'starts_at',
        'ends_at',
        'max_total_uses',
        'max_uses_per_user',
        'offer_code',
        'image',
        'status',
        'lifecycle',
    ];


    protected $casts = [
        'is_auto_apply' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];


    /**
     * Fixed Model code
     */


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
        return render_status_badge($this->status, $this->id, route('offers.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('offers.edit', $this->slug));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('offers.show', $this->slug));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('offers.destroy', $this->id));
    }

    // {!! render_delete_button($addon->id, route('offers.destroy', $addon->id)) !!}

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getOfferCodeBadgeAttribute()
    {
        if ($this->offer_code) {
            return '<span class="badge rounded-pill bg-primary">' . $this->offer_code . '</span>';
        } else {
            return '<span class="badge rounded-pill bg-secondary">N/A</span>';
        }
    }

    // In Offer.php (Model)
    public function getFormattedValueAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        } elseif ($this->type === 'flat') {
            return 'Rs ' . $this->value;
        }

        return $this->value;
    }


    /**
     * Fixed Model code
     */

    /**
     * Services associated with this offer (Many-to-Many)
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'offer_service');
    }

    /**
     * Scope: Only active offers
     */
    // public function scopeActive($query)
    // {
    //     return $query->where('status', 'active')->where('lifecycle', 'active');
    // }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Check if the offer is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->ends_at && now()->greaterThan($this->ends_at);
    }

    /**
     * Auto-apply logic (custom, optional)
     */
    public function shouldAutoApply()
    {
        return $this->is_auto_apply && $this->status === 'active';
    }
}
