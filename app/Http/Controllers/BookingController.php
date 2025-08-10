<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\StoreBookingRequest;
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

        // $validated = $request->validate([
        //     'customer_id' => 'required|exists:customers,id',
        //     'appointment_time' => 'required|date',
        //     'offer_id' => 'nullable|exists:offers,id',
        //     'note' => 'nullable|string|max:1000',
        //     'status' => 'required|in:0,1',
        //     'payment_status' => 'required|in:0,1',
        //     'payment_method' => 'required|in:cash,card,wallet,online',

        //     'services' => 'required|array|min:1',
        //     'services.*.service_variant_id' => 'required|exists:service_variants,id',
        //     'services.*.price' => 'required|numeric|min:0',
        //     'services.*.staff_id' => 'nullable|exists:staff,id',

        //     'addons' => 'nullable|array',
        //     'addons.*.addon_id' => 'nullable|exists:addons,id',
        //     'addons.*.price' => 'nullable|numeric|min:0',
        //     'addons.*.staff_id' => 'nullable|exists:staff,id',
        // ]);

        $validated = $request->validated();

        $result = $this->bookingRepository->create($validated);

        if (isset($result['error'])) {
            return back()->withErrors(['offer_id' => $result['error']]);
        }

        return redirect()->back()->with('success', 'Booking created successfully.');




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
            'customer_id' => $validated['customer_id'],
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

            // booking id automatically add here
            $booking->serviceVariants()->attach(
                $service['service_variant_id'], // This is the related model's ID
                [
                    'price'    => $service['price'],
                    'staff_id' => $service['staff_id'] ?? null,
                    'status'   => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        foreach ($addonData as $addon) {

            // booking id automatically add here
            $booking->addons()->attach(
                $addon['addon_id'], // ID from addons table
                [
                    'price'    => $addon['price'],
                    'staff_id' => $addon['staff_id'] ?? null,
                    'status'   => 'pending'
                ]
            );
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
