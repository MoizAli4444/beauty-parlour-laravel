<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{

    use HasFactory, SoftDeletes;

    // Fillable fields
    protected $fillable = [
        'name',
        'designation',
        'testimonial',
        'image',
        'status',
    ];

    const STATUS_PENDING  = 'pending';
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    ///////////// fixed model functions //////////////
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
        return render_status_badge($this->status, $this->id, route('testimonials.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('testimonials.edit', $this->id));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('testimonials.show', $this->id));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('testimonials.destroy', $this->id));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    ///////////// fixed model functions //////////////


}
