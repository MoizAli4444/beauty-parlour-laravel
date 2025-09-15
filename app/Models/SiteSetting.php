<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'favicon',
        'contact_email',
        'contact_phone',
        'contact_address',
        'working_hours',
        'facebook_link',
        'instagram_link',
        'currency',
        'default_image',
        // add more fields here if you uncomment them in migration
    ];
}
