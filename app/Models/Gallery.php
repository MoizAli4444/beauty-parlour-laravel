<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'title',
        'description',
        'file_path',
        'media_type',
        'featured',
        'alt_text',
        'file_size',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
