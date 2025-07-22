<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\User;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;


class CustomerRepository
{
    use TracksUser;

    public function getDatatableData()
    {
        try {
            return DataTables::of(Customer::query()->latest())

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->editColumn('name', function ($row) {
                    return strlen($row->name) > 20 ? substr($row->name, 0, 20) . '...' : $row->name;
                })

                ->editColumn('email', function ($row) {
                    return $row->email;
                })

                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })

                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })

                ->addColumn('action', function ($row) {
                    return view('admin.customer.action', ['customer' => $row])->render();
                })

                ->rawColumns(['checkbox', 'action', 'status'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        return Customer::latest()->get();
    }

    public function find($id)
    {
        return Customer::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Customer::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);
        return Customer::create($data);
    }

    public function update($id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $data = $this->addUpdatedBy($data);
        $customer->update($data);
        return $customer;
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        return $customer->delete();
    }

    public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->save();

        return $customer;
    }

    public function bulkDelete(array $ids)
    {
        return Customer::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Customer::whereIn('id', $ids)->update(['status' => $status]);
    }
}
