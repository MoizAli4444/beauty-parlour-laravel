<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingReview;
use Illuminate\Http\Request;

class BookingReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = BookingReview::with('booking', 'user')->latest()->paginate(10);
        return view('booking_reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookings = Booking::all(); // you might filter only completed bookings
        return view('booking_reviews.create', compact('bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'required|string',
        ]);

        BookingReview::create([
            'booking_id' => $request->booking_id,
            'user_id'    => auth()->id(), // logged-in user
            'rating'     => $request->rating,
            'review'     => $request->review,
            'status'     => 'pending',
        ]);

        return redirect()->route('booking_reviews.index')->with('success', 'Review submitted successfully!');
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
        return view('booking_reviews.edit', compact('bookingReview'));
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

        return redirect()->route('booking_reviews.index')->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookingReview $bookingReview)
    {
        $bookingReview->delete();
        return redirect()->route('booking_reviews.index')->with('success', 'Review deleted successfully!');
    }
}
