<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\ServiceVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get active records with their related models
        $users = User::where('role', 'customer')->get();
        $staff = User::where('role', 'staff')->get();
        $serviceVariants = $variants = ServiceVariant::get();

        $addons = Addon::get();
        $offers = Offer::get();
        return view('admin.booking.create', compact('users', 'variants', 'addons', 'offers', 'serviceVariants', 'staff'));
    }

    public function create_old()
    {
        // get active records with their related models
        $users = User::where('role', 'customer')->get();
        $staff = User::where('role', 'staff')->get();
        $serviceVariants = $variants = ServiceVariant::get();

        $addons = Addon::get();
        $offers = Offer::get();
        return view('admin.booking.create_old', compact('users', 'variants', 'addons', 'offers', 'serviceVariants', 'staff'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store_old(Request $request)
    {
        return $request;
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'appointment_time' => 'required|date',
            'payment_method' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $booking = Booking::create([
                'user_id' => $request->user_id,
                'offer_id' => $request->offer_id,
                'appointment_time' => $request->appointment_time,
                'note' => $request->note,
                'payment_method' => $request->payment_method,
                'status' => 'active',
                'booking_status' => 'booked',
                'payment_status' => 0,
            ]);

            $totalAmount = 0;
            $addonAmount = 0;

            // Service Variants
            foreach ($request->services as $service) {
                $totalAmount += $service['price'];

                $booking->serviceVariants()->create([
                    'service_variant_id' => $service['service_variant_id'],
                    'price' => $service['price'],
                    'staff_id' => $service['staff_id'] ?? null,
                ]);
            }

            // Addons
            if ($request->has('addons')) {
                foreach ($request->addons as $addon) {
                    $addonAmount += $addon['price'];

                    $booking->addons()->create([
                        'addon_id' => $addon['addon_id'],
                        'price' => $addon['price'],
                        'staff_id' => $addon['staff_id'] ?? null,
                    ]);
                }
            }

            $booking->update([
                'addon_amount' => $addonAmount,
                'total_amount' => $totalAmount + $addonAmount, // apply discounts/tax if needed
            ]);

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
