<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait TracksUser
{
    public function addCreatedBy(array $data): array
    {
        $data['created_by'] = Auth::user()?->id;
        return $data;
    }

    public function addUpdatedBy(array $data): array
    {
        $data['updated_by'] = Auth::user()?->id;
        return $data;
    }
}
