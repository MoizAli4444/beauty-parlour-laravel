<?php

namespace App\Repositories;


use App\Interfaces\AddonRepositoryInterface;
use App\Models\Addon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use App\Traits\TracksUser;

class AddonRepository implements AddonRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

        use TracksUser;

    public function getDatatableData()
    {
        try {
            return DataTables::of(Addon::query()->latest())

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->editColumn('name', function ($row) {
                    return strlen($row->name) > 20 ? substr($row->name, 0, 20) . '...' : $row->name;
                })

                ->editColumn('status', function ($row) {
                    return $row->status_badge; // uses model accessor
                })

                 ->editColumn('gender', function ($row) {
                    return $row->gender_badge ; // uses model accessor
                })

                 ->editColumn('price', function ($row) {
                    return 'Rs ' . number_format($row->price, 2);
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y'); // Example: 29 Jun 2025
                })

                ->addColumn('action', function ($row) {
                    return view('admin.addon.action', ['addon' => $row])->render();
                })
                ->rawColumns(['checkbox', 'action', 'status','gender']) // allow HTML rendering
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function all()
    {
        return Addon::latest()->get();
    }

    public function find($id)
    {
        return Addon::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Addon::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);
        return Addon::create($data);
    }

    public function update($id, array $data)
    {
        $addon = Addon::findOrFail($id);
        $data = $this->addUpdatedBy($data);
        $addon->update($data);
        return $addon;
    }

    public function delete($id)
    {
        $addon = Addon::findOrFail($id);
        return $addon->delete(); // uses softDeletes
    }

    public function toggleStatus($id)
    {
        $addon = Addon::findOrFail($id);
        $addon->status = $addon->status === 'active' ? 'inactive' : 'active';
        $addon->save();

        return $addon;
    }

    public function bulkDelete(array $ids)
    {
        return Addon::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Addon::whereIn('id', $ids)->update(['status' => $status]);
    }
}
