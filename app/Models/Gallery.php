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

    public function getFeaturedBadgeAttribute(): string
    {
        return $this->renderFeaturedBadge($this->featured, $this->id, route('galleries.toggleFeatured', $this->id));
    }

    private function renderFeaturedBadge($featured, $id = null, $route = null): string
    {
        $text  = $featured ? 'Yes' : 'No';
        $class = $featured ? 'success' : 'secondary';

        if ($id && $route) {
            return "<a href='javascript:void(0)' 
                    data-id='{$id}' 
                    data-route='{$route}' 
                    class='toggle-featured badge rounded-pill text-bg-{$class}' 
                    style='cursor:pointer'>{$text}</a>";
        }

        return "<span class='badge rounded-pill text-bg-{$class}'>{$text}</span>";
    }




    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
