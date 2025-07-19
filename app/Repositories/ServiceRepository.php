<?php

namespace App\Repositories;

use App\Models\Service;
use App\Interfaces\ServiceRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use App\Traits\TracksUser;


class ServiceRepository implements ServiceRepositoryInterface
{
    use TracksUser;

    public function getDatatableData()
    {
        try {
            return DataTables::of(Service::query()->latest())

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->editColumn('name', function ($row) {
                    return strlen($row->name) > 20 ? substr($row->name, 0, 20) . '...' : $row->name;
                })

                ->editColumn('status', function ($row) {
                    return $row->status_badge; // uses model accessor
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y'); // Example: 29 Jun 2025
                })

                ->addColumn('action', function ($row) {
                    return view('admin.service.action', ['service' => $row])->render();
                })
                ->rawColumns(['checkbox', 'action', 'status']) // allow HTML rendering
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function all()
    {
        return Service::latest()->get();
    }

    public function find($id)
    {
        return Service::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Service::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);
        return Service::create($data);
    }

    public function update($id, array $data)
    {
        $service = Service::findOrFail($id);
        $data = $this->addUpdatedBy($data);
        $service->update($data);
        return $service;
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        return $service->delete(); // uses softDeletes
    }
}
