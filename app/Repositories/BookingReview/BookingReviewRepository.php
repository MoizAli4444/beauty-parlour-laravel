<?php

namespace App\Repositories\BookingReview;

use App\Models\BookingReview;

use App\Repositories\BookingReview\BookingReviewRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;

class BookingReviewRepository implements BookingReviewRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    use TracksUser;

    public function getDatatableData(array $filters)
    {

        try {
            $query = BookingReview::with(['customer.user', 'booking', 'moderator'])->latest();

            // ✅ Filters
            if (!empty($filters['customer_id'])) {
                $query->where('customer_id', $filters['customer_id']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['rating'])) {
                // $query->where('rating', $filters['rating']);
                $query->where('rating', (int) $filters['rating']);
            }



            // ✅ DataTable response
            return DataTables::of($query)

                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn('customer', function ($row) {
                    return $row->customer && $row->customer->user
                        ? $row->customer->user->name
                        : 'N/A';
                })

                ->addColumn('booking', fn($row) => $row->booking ? 'Booking #' . $row->booking->id : 'N/A')

                ->addColumn('rating', fn($row) => str_repeat('⭐', $row->rating))

                ->editColumn('review', fn($row) => substr($row->review, 0, 50) . '...')

                ->editColumn('status', function ($row) {
                    $color = match ($row->status) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    };
                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
                })


                ->addColumn('moderator_name', function ($row) {
                    if ($row->moderator_type === 'App\\Models\\Staff') {
                        return optional(optional($row->moderator)->user)->name ?? 'N/A';
                    }

                    if ($row->moderator_type === 'App\\Models\\User') {
                        return optional($row->moderator)->name ?? 'N/A';
                    }

                    return 'N/A';
                })


                ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))

                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.booking-reviews.action', ['booking_review' => $row])->render()
                )

                ->rawColumns(['checkbox', 'status', 'action', 'rating', 'moderator_name'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function all()
    {
        return BookingReview::latest()->get();
    }

    public function find($id)
    {
        return BookingReview::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return BookingReview::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);
        return BookingReview::create($data);
    }

    public function update($id, array $data)
    {
        $addon = BookingReview::findOrFail($id);
        $data = $this->addUpdatedBy($data);
        $addon->update($data);
        return $addon;
    }

    public function delete($id)
    {
        $addon = BookingReview::findOrFail($id);
        return $addon->delete(); // uses softDeletes
    }

    public function toggleStatus($id)
    {
        $addon = BookingReview::findOrFail($id);
        $addon->status = $addon->status === 'active' ? 'inactive' : 'active';
        $addon->save();

        return $addon;
    }

    public function bulkDelete(array $ids)
    {
        return BookingReview::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return BookingReview::whereIn('id', $ids)->update(['status' => $status]);
    }
}
