<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingReview;
use App\Models\Customer;
use App\Repositories\BookingReview\BookingReviewRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingReviewController extends Controller
{

    protected $bookingReviewRepo;

    public function __construct(BookingReviewRepositoryInterface $bookingReviewRepo)
    {
        $this->bookingReviewRepo = $bookingReviewRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['customer_id', 'status', 'rating']);
            return $this->bookingReviewRepo->getDatatableData($filters);
        }

        return abort(403);
    }


    public function index()
    {
        $review_statuses = BookingReview::STATUSES;
        $customers = Customer::active()->with('user:id,name')->get(['id', 'user_id']);
        return view('admin.booking-reviews.index', compact('customers', 'review_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookings = Booking::all(); // you might filter only completed bookings
        return view('booking-reviewss.create', compact('bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'nullable|string|max:1000',
        ]);

        $review = BookingReview::create([
            'booking_id' => $request->booking_id,
            'customer_id' => Auth::id(), // assuming logged-in customer
            'rating'     => $request->rating,
            'review'     => $request->review,
            'status'     => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully and pending approval.',
            'data'    => $review
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BookingReview $bookingReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookingReview $bookingReview)
    {
        return view('booking-reviewss.edit', compact('bookingReview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BookingReview $bookingReview)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $bookingReview->update($request->all());

        return redirect()->route('booking-reviewss.index')->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookingReview $bookingReview)
    {
        $bookingReview->delete();
        return redirect()->route('booking-reviewss.index')->with('success', 'Review deleted successfully!');
    }

    /**
     * Approve or reject a review by Admin/Staff.
     */
    public function moderate(Request $request, BookingReview $review)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $review->update([
            'status'         => $request->status,
            'moderator_id'   => Auth::id(),
            'moderator_type' => Auth::user()::class, // automatically saves Admin or Staff
        ]);

        return response()->json([
            'success' => true,
            'message' => "Review {$request->status} successfully.",
            'data'    => $review->load('moderator'),
        ]);
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review = BookingReview::findOrFail($id);

        // Get logged-in user
        $user = auth()->user();

        // Save moderator info
        $review->status = $request->status;
        $review->moderator_id = $user->id;
        $review->moderator_type = get_class($user); // e.g. App\Models\Admin or App\Models\Staff
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review status updated successfully.',
            'data'    => $review
        ]);
    }
}
