<?php

namespace App\Repositories;

use App\Models\ServiceVariant;

use App\Interfaces\ServiceVariantRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use App\Traits\TracksUser;


class ServiceVariantRepository implements ServiceVariantRepositoryInterface
{
    protected $model;

    public function __construct(ServiceVariant $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('service')->latest()->get();
    }

    public function find($id)
    {
        return $this->model->with('service')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $variant = $this->find($id);
        $variant->update($data);
        return $variant;
    }

    public function delete($id)
    {
        $variant = $this->find($id);
        return $variant->delete();
    }
}
