<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'author_id',
        'service_id',
        'published_at',
        'status',
        'views',
    ];

    // Only published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // 🔹 Status constants
    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT     = 'draft';


    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    ///////////// fixed model functions //////////////
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'question',
                'onUpdate' => true
            ]
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusBadgeAttribute()
    {
        return render_status_badge($this->status, $this->id, route('faqs.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('faqs.edit', $this->slug));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('faqs.show', $this->slug));
    }

    public function renderButton($isOutline = true): string
    {
        $route = route('faqs.edit', $this->slug);

        $btnClass = $isOutline ? 'btn-sm btn-outline-info' : 'btn-info';

        return "<a href='{$route}' class='btn {$btnClass} me-2'>
                    <i class='fas fa-eye'></i> View / Edit
                </a>";
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('faqs.destroy', $this->id));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    ///////////// fixed model functions //////////////

}
