<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Faq extends Model
{
    use Sluggable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question',
        'slug',
        'answer',
        'status',
    ];

    // 🔹 Status constants
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';


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

    /**
     * Accessor: Format created_at nicely.
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y, h:i A') : null;
    }
}
