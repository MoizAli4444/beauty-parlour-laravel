<?php

// app/Repositories/BookingRepository.php

namespace App\Repositories;

use App\Models\Booking;
use App\Interfaces\BookingRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use App\Traits\TracksUser;

class BookingRepository implements BookingRepositoryInterface
{
    use TracksUser;

    public function getDatatableData()
    {
        try {
            return DataTables::of(Booking::query()->latest())

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
                    return $row->gender_badge; // uses model accessor
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
                ->rawColumns(['checkbox', 'action', 'status', 'gender']) // allow HTML rendering
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        return Booking::latest()->get();
    }

    public function find($id)
    {
        return Booking::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Booking::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);
        return Booking::create($data);
    }

    public function update($id, array $data)
    {
        $booking = Booking::findOrFail($id);
        $data = $this->addUpdatedBy($data);
        $booking->update($data);
        return $booking;
    }


    public function delete($id)
    {
        $booking = Booking::findOrFail($id);
        return $booking->delete(); // uses softDeletes
    }

    public function toggleStatus($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = $booking->status === 'active' ? 'inactive' : 'active';
        $booking->save();

        return $booking;
    }

    public function bulkDelete(array $ids)
    {
        return Booking::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Booking::whereIn('id', $ids)->update(['status' => $status]);
    }
}
