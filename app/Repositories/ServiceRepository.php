<?php

namespace App\Repositories;

use App\Models\Service;
use App\Interfaces\ServiceRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;


class ServiceRepository implements ServiceRepositoryInterface
{
    
    public function getDatatableData()
    {
        return DataTables::of(Service::query())
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
            ->rawColumns(['action', 'status']) // allow HTML rendering
            ->make(true);
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
