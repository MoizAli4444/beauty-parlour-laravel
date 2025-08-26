<?php

namespace App\Repositories\BookingReview;

use App\Models\BookingReview;

use App\Repositories\BookingReview\BookingReviewRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Str;

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

            if (!empty($filters['rating_from'])) {
                $query->where('rating', '>=', $filters['rating_from']);
            }

            if (!empty($filters['rating_to'])) {
                $query->where('rating', '<=', $filters['rating_to']);
            }

            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
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

                ->editColumn('review', fn($row) => Str::limit($row->review, 50))

                ->editColumn('status', function ($row) {
                    $color = match ($row->status) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    };
                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
                })

                ->addColumn('moderator', fn($row) => $row->moderator?->name ?? 'N/A')

                ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))

                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.booking-reviews.action', ['review' => $row])->render()
                )

                ->rawColumns(['checkbox', 'status', 'action', 'rating'])
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
