<?php

namespace App\Repositories;

use App\Models\ServiceVariant;

use App\Interfaces\ServiceVariantRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use App\Traits\TracksUser;


class ServiceVariantRepository implements ServiceVariantRepositoryInterface
{
    use TracksUser;

    protected $model;

    public function __construct(ServiceVariant $model)
    {
        $this->model = $model;
    }

    public function getDatatableData()
    {
        try {

            return DataTables::of($this->model->with('service')->latest())

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->editColumn('name', function ($row) {
                    return strlen($row->name) > 20 ? substr($row->name, 0, 20) . '...' : $row->name;
                })

                ->addColumn('service', function ($row) {
                    return $row->service?->name ?? '-';
                })


                ->editColumn('price', function ($row) {
                    return '₨' . number_format($row->price, 2);
                })

                ->editColumn('duration', function ($row) {
                    return $row->duration ?? '-';
                })

                ->editColumn('status', function ($row) {
                    return $row->status_badge; // uses model accessor
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })

                ->addColumn('action', function ($row) {
                    return view('admin.service-variants.action', ['variant' => $row])->render();
                })

                ->rawColumns(['service', 'checkbox', 'status', 'action'])

                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function all()
    {
        return $this->model->with('service')->latest()->get();
    }


    public function find($id)
    {
        return $this->model->with('service')->findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return $this->model->with('service')->where('slug', $slug)->first();
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
