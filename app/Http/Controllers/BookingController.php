<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Interfaces\BookingRepositoryInterface;
use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingServiceVariant;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\ServiceVariant;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    protected $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    // public function datatable(Request $request)
    // {
    //     if ($request->ajax()) {
    //         return $this->bookingRepository->getDatatableData($request);
    //     }

    //     return abort(403);
    // }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['customer_id', 'status', 'payment_status', 'date_from', 'date_to']);
            return $this->bookingRepository->getDatatableData($filters);
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $booking_statuses = Booking::STATUSES;
        $customers = Customer::active()->with('user:id,name')->get(['id', 'user_id']);
        return view('admin.booking.index', compact('customers', 'booking_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $customers = Customer::active()->with('user:id,name')->get(['id', 'user_id']);
        $staffMembers = Staff::active()->with('user:id,name')->get(['id', 'user_id']);

        $serviceVariants = ServiceVariant::active()->get();

        $addons = Addon::active()->get();
        $offers = Offer::active()->get();
        return view('admin.booking.create', compact('customers', 'addons', 'offers', 'serviceVariants', 'staffMembers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest  $request)
    {

        $validated = $request->validated();

        $result = $this->bookingRepository->create($validated);

        if (isset($result['error'])) {
            return back()->withErrors(['offer_id' => $result['error']]);
        }

        return redirect()->back()->with('success', 'Booking created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $booking = Booking::with('customer.user')->findOrFail($id);
        $services = ServiceVariant::all();
        $addons = Addon::all();
        return view('bookings.edit', compact('booking', 'services', 'addons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        $validated = $request->validated();

        $result = $this->bookingRepository->update($id, $validated);

        if (isset($result['error'])) {
            return back()->withErrors(['offer_id' => $result['error']]);
        }

        return redirect()->back()->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if (in_array($booking->status, ['in_progress', 'completed'])) {
            return back()->with('error', 'Cannot delete bookings in progress or completed.');
        }

        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }


    // BookingController.php
    public function indexFilter(Request $request)
    {
        $query = Booking::with(['customer.user'])
            ->orderBy('appointment_time', 'desc');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('appointment_time', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        }

        // Customer filter
        if ($request->filled('customer_name')) {
            $query->whereHas('customer.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        $bookings = $query->paginate(10);

        return view('bookings.index', compact('bookings'));
    }



    // public function update(Request $request, $id)
    // {
    //     $booking = Booking::findOrFail($id);

    //     $validated = $request->validate([
    //         'appointment_time' => 'required|date',
    //         'status'           => 'required|in:pending,confirmed,in_progress,completed,cancelled,rejected',
    //         'payment_status'   => 'required|in:0,1',
    //         'payment_method'   => 'nullable|in:cash,card,wallet,online',
    //         'note'             => 'nullable|string',
    //         'service_variant_amount' => 'required|numeric|min:0',
    //         'addon_amount'     => 'required|numeric|min:0',
    //         'discount'         => 'nullable|numeric|min:0'
    //     ]);

    //     $validated['subtotal'] = $validated['service_variant_amount'] + $validated['addon_amount'];
    //     $validated['total_amount'] = $validated['subtotal'] - $validated['discount'];

    //     $booking->update($validated);

    //     return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    // }

    // make function for booking status , payment status and etc if other status are present
    public function changeStatus($id, $status)
    {
        $booking = Booking::findOrFail($id);

        // Restrict flow if needed
        if ($booking->status === 'completed' || $booking->status === 'cancelled') {
            return back()->with('error', 'Cannot change status for completed or cancelled bookings.');
        }

        // Mark payment as paid if completed
        if ($status === 'completed' && $booking->payment_status == 0) {
            $booking->payment_status = 1;
        }

        $booking->status = $status;
        $booking->save();

        return back()->with('success', 'Booking status updated to ' . ucfirst($status));
    }

    // booking cancel function
    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'cancel_reason' => 'required|string|min:5'
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->cancel_reason
        ]);

        // Optional refund logic
        if ($booking->payment_method === 'online' && $booking->payment_status == 1) {
            // refund logic here...
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
