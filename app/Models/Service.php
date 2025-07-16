<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Service extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
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
    //     return render_status_badge($this->status);  // from helper
    // }

    public function getStatusBadgeAttribute()
    {
        // Pass ID and route name for dynamic toggle link
        return render_status_badge($this->status, $this->id, route('services.toggle-status', $this->id));
    }
}
