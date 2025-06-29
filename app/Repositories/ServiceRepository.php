<?php

namespace App\Repositories;

use App\Models\Service;
use App\Interfaces\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function all()
    {
        return Service::latest()->get();
    }

    public function find($id)
    {
        return Service::findOrFail($id);
    }

    public function create(array $data)
    {
        return Service::create($data);
    }

    public function update($id, array $data)
    {
        $service = Service::findOrFail($id);
        $service->update($data);
        return $service;
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        return $service->delete(); // uses softDeletes
    }
}
