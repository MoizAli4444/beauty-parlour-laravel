<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\ServiceVariant;
use App\Models\User;
use Illuminate\Http\Request;

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
        $users = User::where('role','customer')->get();
        $variants = ServiceVariant::get();
        $addons = Addon::get();
        return view('admin.booking.create',compact('users','variants','addons'));
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
    {
        return $request;
    }

//     use App\Models\Service;
// use App\Models\Addon;
// use Illuminate\Http\Request;

// public function createBooking(Request $request)
// {
//     // Validate request
//     $request->validate([
//         'service_id' => 'required|exists:services,id',
//         'addon_ids' => 'array',
//         'addon_ids.*' => 'exists:addons,id',
//         'tip_amount' => 'nullable|numeric|min:0',
//     ]);

//     // Step 1: Get base values
//     $service        = Service::findOrFail($request->service_id);
//     $servicePrice   = $service->price;
//     $tipAmount      = $request->input('tip_amount', 0);

//     // Step 2: Fixed 5% discount on service price
//     $discountPercent = 5;
//     $discount        = ($discountPercent / 100) * $servicePrice;

//     // Step 3: Tax (e.g. 13%)
//     $taxRate = 13; // Could also fetch from config
//     $tax     = ($taxRate / 100) * $servicePrice;

//     // Step 4: Addons (sum of prices of selected addons)
//     $addonAmount = Addon::whereIn('id', $request->addon_ids ?? [])->sum('price');

//     // Step 5: Calculate payable and total
//     $payableAmount = ($servicePrice - $discount) + $tax;
//     $totalAmount   = $payableAmount + $addonAmount + $tipAmount;

//     // Optional: Store booking in DB (replace `Booking` with your model)
//     $booking = Booking::create([
//         'user_id'        => auth()->id(),
//         'service_id'     => $service->id,
//         'service_price'  => $servicePrice,
//         'discount'       => $discount,
//         'tax'            => $tax,
//         'addon_amount'   => $addonAmount,
//         'tip_amount'     => $tipAmount,
//         'payable_amount' => $payableAmount,
//         'total_amount'   => $totalAmount,
//     ]);

//     return response()->json([
//         'message' => 'Booking created successfully',
//         'data'    => $booking
//     ]);
// }


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
