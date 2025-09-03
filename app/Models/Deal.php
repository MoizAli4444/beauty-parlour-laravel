<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
 use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'services_total',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationships
     */
    public function serviceVariants()
    {
        return $this->belongsToMany(ServiceVariant::class, 'deal_service')
                    ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
