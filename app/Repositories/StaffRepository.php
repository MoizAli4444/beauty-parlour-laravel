<?php

namespace App\Repositories;

use App\Interfaces\StaffRepositoryInterface;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use App\Traits\TracksUser;
use Yajra\DataTables\Facades\DataTables;


class StaffRepository  implements StaffRepositoryInterface
{
    use TracksUser;

    public function getDatatableData()
    {
        try {
            return DataTables::of(Staff::query()->latest())
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->editColumn('name', function ($row) {
                    return strlen($row->name) > 20 ? substr($row->name, 0, 20) . '...' : $row->name;
                })
                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return view('admin.staff.action', ['staff' => $row])->render();
                })
                ->rawColumns(['checkbox', 'action', 'status'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        return Staff::latest()->get();
    }

    public function find($id)
    {
        return Staff::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Staff::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);
        return Staff::create($data);
    }

    public function update($id, array $data)
    {
        $staff = Staff::findOrFail($id);
        $data = $this->addUpdatedBy($data);
        $staff->update($data);
        return $staff;
    }

    public function delete($id)
    {
        $staff = Staff::findOrFail($id);
        return $staff->delete();
    }

    public function toggleStatus($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->status = $staff->status === 'active' ? 'inactive' : 'active';
        $staff->save();

        return $staff;
    }

    public function bulkDelete(array $ids)
    {
        return Staff::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Staff::whereIn('id', $ids)->update(['status' => $status]);
    }
}
