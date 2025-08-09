<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingServiceVariant;
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

    public function store_0(Request $request)
    {
        $startTime = microtime(true);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'appointment_time' => 'required|date',
            'offer_id' => 'nullable|exists:offers,id',
            'note' => 'nullable|string|max:1000',
            'status' => 'required|in:0,1',
            'payment_status' => 'required|in:0,1',
            'payment_method' => 'required|in:cash,card,wallet,online',

            'services' => 'required|array|min:1',
            'services.*.service_variant_id' => 'required|exists:service_variants,id',
            'services.*.price' => 'required|numeric|min:0',
            'services.*.staff_id' => 'nullable|exists:users,id',

            'addons' => 'nullable|array',
            'addons.*.addon_id' => 'nullable|exists:addons,id',
            'addons.*.price' => 'nullable|numeric|min:0',
            'addons.*.staff_id' => 'nullable|exists:users,id',
        ]);

        // Calculate service total
        $serviceTotal = 0;
        foreach ($validated['services'] as $service) {
            $variant = ServiceVariant::find($service['service_variant_id']);
            $price = $variant ? $variant->price : 0;
            $serviceTotal += $price;
        }

        // Calculate addon total
        $addonTotal = 0;
        if (!empty($validated['addons'])) {
            foreach ($validated['addons'] as $addon) {
                if (!empty($addon['addon_id'])) {
                    $addonModel = Addon::find($addon['addon_id']);
                    $price = $addonModel ? $addonModel->price : 0;
                    $addonTotal += $price;
                }
            }
        }



        $subtotal = $serviceTotal + $addonTotal;

        // Apply offer
        $discount = 0;
        $validZero = false;
        $finalTotal = $subtotal;

        if (!empty($validated['offer_id'])) {
            $offer = Offer::find($validated['offer_id']);
            if ($offer) {
                if ($offer->type === 'percentage') {
                    $discount = $subtotal * ($offer->value / 100);
                } elseif ($offer->type === 'flat') {
                    $discount = $offer->value;
                }

                $finalTotal = $subtotal - $discount;

                if ($finalTotal <= 0 && $subtotal > 0) {
                    if (
                        ($offer->type === 'percentage' && $offer->value == 100) ||
                        ($offer->type === 'flat' && $offer->value == $subtotal)
                    ) {
                        $validZero = true;
                    }

                    if (!$validZero) {
                        return back()->withErrors(['offer_id' => '⚠️ Discount too high! Total is $0.00. Please review the offer.']);
                    }

                    $finalTotal = 0;
                }
            }
        }


        return round(microtime(true) - $startTime, 4);

        return [
            'validated' => $validated,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'finalTotal' => $finalTotal,
            'serviceTotal' => $serviceTotal,
            'addonTotal' => $addonTotal
        ];


        // Create booking
        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'appointment_time' => $validated['appointment_time'],
            'offer_id' => $validated['offer_id'] ?? null,
            'note' => $validated['note'] ?? null,
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $finalTotal,
        ]);

        // Optionally store related services/addons
        // BookingService::create([...])
        // BookingAddon::create([...])

        // ✅ Save related services
        foreach ($serviceData as $service) {
            $booking->serviceVariants()->create($service);
        }

        // ✅ Save related addons
        foreach ($addonData as $addon) {
            $booking->addons()->create($addon);
        }

        return redirect()->back()->with('success', 'Booking created successfully.');
    }

    public function store(Request $request)
    {
        $startTime = microtime(true);

        // return $request;

        // ✅ Validate only IDs, not prices from frontend
        // $validated = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'appointment_time' => 'required|date',
        //     'offer_id' => 'nullable|exists:offers,id',
        //     'note' => 'nullable|string|max:1000',
        //     'status' => 'required|in:0,1',
        //     'payment_status' => 'required|in:0,1',
        //     'payment_method' => 'required|in:cash,card,wallet,online',

        //     'services' => 'required|array|min:1',
        //     'services.*.service_variant_id' => 'required|exists:service_variants,id',
        //     'services.*.staff_id' => 'nullable|exists:users,id',

        //     'addons' => 'nullable|array',
        //     'addons.*.addon_id' => 'required_with:addons|exists:addons,id',
        //     'addons.*.staff_id' => 'nullable|exists:users,id',
        // ]);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'appointment_time' => 'required|date',
            'offer_id' => 'nullable|exists:offers,id',
            'note' => 'nullable|string|max:1000',
            'status' => 'required|in:0,1',
            'payment_status' => 'required|in:0,1',
            'payment_method' => 'required|in:cash,card,wallet,online',

            'services' => 'required|array|min:1',
            'services.*.service_variant_id' => 'required|exists:service_variants,id',
            'services.*.price' => 'required|numeric|min:0',
            'services.*.staff_id' => 'nullable|exists:users,id',

            'addons' => 'nullable|array',
            'addons.*.addon_id' => 'nullable|exists:addons,id',
            'addons.*.price' => 'nullable|numeric|min:0',
            'addons.*.staff_id' => 'nullable|exists:users,id',
        ]);

        // ✅ Fetch all related models in batch to reduce DB queries
        $variantIds = collect($validated['services'])->pluck('service_variant_id');
        $variants = ServiceVariant::whereIn('id', $variantIds)->get()->keyBy('id');

        $addonTotal = 0;
        $serviceTotal = 0;

        // ✅ Build service records
        $serviceData = [];
        foreach ($validated['services'] as $service) {
            $variant = $variants[$service['service_variant_id']];
            $price = $variant->price;
            $serviceTotal += $price;

            $serviceData[] = [
                'service_variant_id' => $variant->id,
                'price' => $price,
                'staff_id' => $service['staff_id'] ?? null,
            ];
        }

        $addonData = [];
        if (!empty($validated['addons'])) {
            $addonIds = collect($validated['addons'])->pluck('addon_id');
            $addons = Addon::whereIn('id', $addonIds)->get()->keyBy('id');

            foreach ($validated['addons'] as $addon) {
                if (!empty($addon['addon_id'])) {
                    $addonModel = $addons[$addon['addon_id']];
                    $price = $addonModel->price;
                    $addonTotal += $price;

                    $addonData[] = [
                        'addon_id' => $addonModel->id,
                        'price' => $price,
                        'staff_id' => $addon['staff_id'] ?? null,
                    ];
                }
            }
        }

        $subtotal = $serviceTotal + $addonTotal;

        // ✅ Calculate discount securely
        $discount = 0;
        $validZero = false;
        $finalTotal = $subtotal;

        if (!empty($validated['offer_id'])) {
            $offer = Offer::find($validated['offer_id']);
            if ($offer) {
                if ($offer->type === 'percentage') {
                    $discount = $subtotal * ($offer->value / 100);
                } elseif ($offer->type === 'flat') {
                    $discount = $offer->value;
                }

                $finalTotal = $subtotal - $discount;

                if ($finalTotal <= 0 && $subtotal > 0) {
                    if (
                        ($offer->type === 'percentage' && $offer->value == 100) ||
                        ($offer->type === 'flat' && $offer->value == $subtotal)
                    ) {
                        $validZero = true;
                    }

                    if (!$validZero) {
                        return back()->withErrors(['offer_id' => '⚠️ Discount too high! Total is $0.00. Please review the offer.']);
                    }

                    $finalTotal = 0;
                }
            }
        }
       

        // ✅ Create booking
        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'appointment_time' => $validated['appointment_time'],
            'offer_id' => $validated['offer_id'] ?? null,
            'note' => $validated['note'] ?? null,
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $finalTotal,

            'addon_amount' => $addonTotal,
            'service_variant_amount' => $serviceTotal,
        ]);

        // ✅ Save related services
        foreach ($serviceData as $service) {
            // $booking->serviceVariants()->create($service);

            // $booking->serviceVariants()->attach(
            //     $service['service_variant_id'], // This is the related model's ID
            //     [
            //         'price'    => $service['price'],
            //         'staff_id' => $service['staff_id'] ?? null,
            //         'status'   => 'pending',
            //         'created_at' => now(),
            //         'updated_at' => now()
            //     ]
            // );

            $staff_id = $request->staff_id ?? null;

            BookingServiceVariant::create([
                'booking_id' => $booking->id,
                'service_variant_id' => $service['service_variant_id'],
                'price' => $price,
                'staff_id' => $staff_id, // will be NULL if not provided
                'status' => 'pending',
            ]);
        }

        // ✅ Save related addons
        // foreach ($addonData as $addon) {
        //     // $booking->addons()->create($addon);
        //     $booking->addons()->create($addon);
        // }

        foreach ($addonData as $addon) {

            $staff_id = $request->staff_id ?: null;


            $booking->addons()->attach(
                $addon['addon_id'], // ID from addons table
                [
                    'price'    => $addon['price'],
                    'staff_id' => $staff_id,
                    'status'   => 'pending'
                ]
            );


            // BookingAddon::create([
            //     'addon_id'  => $addon['addon_id'],
            //     'booking_id' => $booking->id,
            //     'price'     => $price,
            //     'staff_id'  => $staff_id, // null if none
            //     'status'    => 'pending'
            // ]);
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
