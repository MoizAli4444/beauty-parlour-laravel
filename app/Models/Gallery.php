<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;


class Gallery extends Model
{
    use Sluggable, HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // protected $fillable = [
    //     'service_id',
    //     'title',
    //     'slug',
    //     'description',
    //     'file_path',
    //     'media_type',
    //     'featured',
    //     'alt_text',
    //     'file_size',
    //     'status',
    // ];
    protected $fillable = [
        'service_id',
        'title',
        'slug',
        'description',
        'file_path',
        'media_type',
        'featured',
        'alt_text',
        'file_size',
        'status',
        'created_by',
        'updated_by',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true
            ]
        ];
    }

    /////////////////////////////////////////////////////////////
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
        return render_status_badge($this->status, $this->id, route('galleries.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('galleries.edit', $this->slug));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('galleries.show', $this->slug));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('galleries.destroy', $this->id));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    /////////////////////////////////////////////////////////////

    public function getFeaturedBadgeAttribute()
    {
        if ($this->featured) {
            return '<span class="badge bg-success">Yes</span>';
        }

        return '<span class="badge bg-secondary">No</span>';
    }



    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
