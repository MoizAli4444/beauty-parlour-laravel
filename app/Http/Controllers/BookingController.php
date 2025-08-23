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

    public function show($id)
    {
        $booking = Booking::with([
            'serviceVariants' => function ($q) {
                $q->select('service_variants.id', 'service_variants.service_id', 'service_variants.name', 'service_variants.price', 'service_variants.duration');
            },
            'addons' => function ($q) {
                $q->select('addons.id', 'addons.name', 'addons.price');
            },
            'offer' => function ($q) {
                $q->select('offers.id', 'offers.name', 'offers.type', 'offers.value', 'offers.starts_at', 'offers.ends_at', 'offers.max_total_uses');
            },
            // 'customer' // you can also trim this if needed
        ])->findOrFail($id);

        // return $booking;
        if (!$booking) {
            return redirect()->route('bookings.index')->with('error', 'Booking not found.');
        }

        return view('admin.booking.show', compact('booking'));
    }


    public function showold($id)
    {

        $booking = Booking::with([
            'serviceVariants.pivot.staff.user',
            'addons.pivot.staff.user',
            'offer',
            'customer'
        ])->findOrFail($id);

        return $booking;

        $booking = Booking::with('serviceVariants', 'addons', 'offer', 'customer')->find($id);

        foreach ($booking->serviceVariants as $sv) {
            $staffId = $sv->pivot->staff_id ?? null;
            if ($staffId) {

                $staff = Staff::with('user')->find($staffId);

                $sv->pivot->staff_name = $staff->user->name ?? null;
            } else {

                $sv->pivot->staff_name = null;
            }
        }


        foreach ($booking->addons as $ad) {
            $staffId = $ad->pivot->staff_id ?? null;
            if ($staffId) {

                $staff = Staff::with('user')->find($staffId);

                $ad->pivot->staff_name = $staff->user->name ?? null;
            } else {

                $ad->pivot->staff_name = null;
            }
        }
        // return $booking;
        if (!$booking) {
            return redirect()->route('bookings.index')->with('error', 'Booking not found.');
        }

        return view('admin.booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $booking = Booking::with([
            'serviceVariants' => function ($q) {
                $q->select('service_variants.id', 'service_variants.service_id', 'service_variants.name', 'service_variants.price', 'service_variants.duration');
            },
            'addons' => function ($q) {
                $q->select('addons.id', 'addons.name', 'addons.price');
            },
            'offer' => function ($q) {
                $q->select('offers.id', 'offers.name', 'offers.type', 'offers.value', 'offers.starts_at', 'offers.ends_at', 'offers.max_total_uses');
            },
            // 'customer' // you can also trim this if needed
        ])->findOrFail($id);

        // return $booking;
        if (!$booking) {
            return redirect()->route('bookings.index')->with('error', 'Booking not found.');
        }


        $customers = Customer::active()->with('user:id,name')->get(['id', 'user_id']);
        $staffMembers = Staff::active()->with('user:id,name')->get(['id', 'user_id']);

        $serviceVariants = ServiceVariant::active()->get();

        $addons = Addon::active()->get();
        $offers = Offer::active()->get();

        return view('admin.booking.edit', compact('booking', 'customers', 'addons', 'offers', 'serviceVariants', 'staffMembers'));
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

    // make function for booking status , payment status and etc if other status are present
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled,rejected'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;

        if ($request->status === 'cancelled') {
            $booking->cancel_reason = $request->cancel_reason ?? 'Cancelled by admin';
        }

        $booking->updated_by = auth()->id();
        $booking->save();

        return response()->json(['success' => true, 'status' => $booking->status]);
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
