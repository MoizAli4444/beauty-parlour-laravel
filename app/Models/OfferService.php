<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferService extends Model
{
    public $timestamps = false;

    protected $table = 'offer_service'; // important for custom pivot tables
}
