<?php

namespace App\Repositories;

use App\Interfaces\StaffRepositoryInterface;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\TracksUser;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


class StaffRepository  implements StaffRepositoryInterface
{
    use TracksUser;

    public function getDatatableData()
    {
        try {
            // return DataTables::of(Staff::with('user')->latest())
            return DataTables::of(Staff::with(['user', 'staffRole', 'shift'])->select('staff.*')->latest())

                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($keyword) . "%"]);
                    });
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->whereRaw('LOWER(email) LIKE ?', ["%" . strtolower($keyword) . "%"]);
                    });
                })
                

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->editColumn('name', function ($row) {
                    return strlen($row->user->name ?? '') > 20
                        ? substr($row->user->name, 0, 20) . '...'
                        : $row->user->name;
                })

                ->editColumn('email', function ($row) {
                    return $row->user->email ?? '-';
                })

                ->editColumn('staff_role', function ($row) {
                    return $row->staffRole?->name ?? '-';
                })

                ->editColumn('shift_name', function ($row) {
                    return $row->shift?->name ?? '-';
                })

                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })

                ->editColumn('joining_date', function ($row) {
                    return $row->joining_date->format('d M Y');
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })

                ->addColumn('action', function ($row) {
                    return view('admin.staff.action', ['staff' => $row])->render();
                })
                ->rawColumns(['checkbox', 'staff_role', 'shift_name', 'action', 'status'])
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
        return User::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return User::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {

                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($data['email']), // temp password
                ]);

                $data = $this->addCreatedBy($data);
                $data['user_id'] = $user->id;
                unset($data['name'], $data['email']); // prevent duplicate columns

                return Staff::create($data);
            });
        } catch (Exception $e) {
            Log::error('Failed to create staff/user: ' . $e->getMessage());
            throw $e;
        }
    }



    public function update($id, array $data)
    {
        try {
            // dd("");
            return DB::transaction(function () use ($id, $data) {

                $user = User::findOrFail($id);
                $user->update(Arr::only($data, ['name', 'email']));

                $user->staff?->update($this->addUpdatedBy(Arr::except($data, ['name', 'email'])));
                return $user->staff;
            });
        } catch (Exception $e) {
            Log::error('Failed to update staff/user: ' . $e->getMessage());
            throw $e;
        }
    }


    public function delete($id)
    {
        // dd($id);
        $user = User::findOrFail($id);
        return $user->delete();
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
