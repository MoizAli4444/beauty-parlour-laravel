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
            return $this->bookingRepository->getDatatableData();
        }

        return abort(403);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.booking.index');
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
        // return $request;

        // $data = $request->all();  // Get all input as array

        // foreach ($data as $key => $value) {
        //     if ($key === '') {
        //         dd('Empty key found!', $data);
        //     }
        // }



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
    public function edit(Booking $booking)
    {
        //
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
    public function destroy(Booking $booking)
    {
        //
    }
}
