<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Deal extends Model
{
    use Sluggable, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'services_total',
        'start_date',
        'end_date',
        'status',
        'image',
        'created_by',
        'updated_by',
    ];

    // 🔹 Status constants
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    // ✅ Cast dates to Carbon
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];


    /**
     * Relationships
     */
    public function serviceVariants()
    {
        return $this->belongsToMany(ServiceVariant::class, 'deal_service')
            ->withTimestamps();
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

    public function getStatusBadgeAttribute()
    {
        return render_status_badge($this->status, $this->id, route('deals.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('deals.edit', $this->id));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('deals.show', $this->id));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('deals.destroy', $this->id));
    }

    ///////////// fixed model functions //////////////



}
