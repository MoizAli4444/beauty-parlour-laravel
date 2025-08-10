<?php

// app/Repositories/BookingRepository.php

namespace App\Repositories;

use App\Models\Booking;
use App\Interfaces\BookingRepositoryInterface;
use App\Models\Addon;
use App\Models\Offer;
use App\Models\ServiceVariant;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use App\Traits\TracksUser;

class BookingRepository implements BookingRepositoryInterface
{
    use TracksUser;

    public function getDatatableData()
    {
        try {
            return DataTables::of(Booking::query()->latest())

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->editColumn('name', function ($row) {
                    return strlen($row->name) > 20 ? substr($row->name, 0, 20) . '...' : $row->name;
                })

                ->editColumn('status', function ($row) {
                    return $row->status_badge; // uses model accessor
                })

                ->editColumn('gender', function ($row) {
                    return $row->gender_badge; // uses model accessor
                })

                ->editColumn('price', function ($row) {
                    return 'Rs ' . number_format($row->price, 2);
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y'); // Example: 29 Jun 2025
                })

                ->addColumn('action', function ($row) {
                    return view('admin.addon.action', ['addon' => $row])->render();
                })
                ->rawColumns(['checkbox', 'action', 'status', 'gender']) // allow HTML rendering
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        return Booking::latest()->get();
    }

    public function find($id)
    {
        return Booking::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Booking::where('slug', $slug)->first();
    }

    public function create(array $data)
    {

        // Fetch related models in batch
        $variantIds = collect($data['services'])->pluck('service_variant_id');
        $variants = ServiceVariant::whereIn('id', $variantIds)->get()->keyBy('id');

        $addonTotal = 0;
        $serviceTotal = 0;
        $serviceData = [];

        foreach ($data['services'] as $service) {
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
        if (!empty($data['addons'])) {
            $addonIds = collect($data['addons'])->pluck('addon_id');
            $addons = Addon::whereIn('id', $addonIds)->get()->keyBy('id');

            foreach ($data['addons'] as $addon) {
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
        $discount = 0;
        $validZero = false;
        $finalTotal = $subtotal;

        if (!empty($data['offer_id'])) {
            $offer = Offer::find($data['offer_id']);
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
                        return ['error' => '⚠️ Discount too high! Total is $0.00. Please review the offer.'];
                    }

                    $finalTotal = 0;
                }
            }
        }

        // Create booking

        $data = $this->addCreatedBy($data);

        $booking = Booking::create([
            'customer_id' => $data['customer_id'],
            'appointment_time' => $data['appointment_time'],
            'offer_id' => $data['offer_id'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => $data['status'],
            'payment_status' => $data['payment_status'],
            'payment_method' => $data['payment_method'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $finalTotal,
            'addon_amount' => $addonTotal,
            'service_variant_amount' => $serviceTotal,
        ]);

        // Attach services
        foreach ($serviceData as $service) {
            $booking->serviceVariants()->attach(
                $service['service_variant_id'],
                [
                    'price' => $service['price'],
                    'staff_id' => $service['staff_id'] ?? null,
                    // 'status' => 'pending',
                ]
            );
        }

        // Attach addons
        foreach ($addonData as $addon) {
            $booking->addons()->attach(
                $addon['addon_id'],
                [
                    'price' => $addon['price'],
                    'staff_id' => $addon['staff_id'] ?? null,
                    // 'status' => 'pending'
                ]
            );
        }

        return $booking;
    }

    // public function update($id, array $data)
    // {
    //     $booking = Booking::findOrFail($id);
    //     $data = $this->addUpdatedBy($data);
    //     $booking->update($data);
    //     return $booking;
    // }

    public function update($bookingId, array $data)
    {
        $booking = Booking::findOrFail($bookingId);

        // Fetch related models in batch
        $variantIds = collect($data['services'])->pluck('service_variant_id');
        $variants = ServiceVariant::whereIn('id', $variantIds)->get()->keyBy('id');

        $addonTotal = 0;
        $serviceTotal = 0;
        $serviceData = [];

        foreach ($data['services'] as $service) {
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
        if (!empty($data['addons'])) {
            $addonIds = collect($data['addons'])->pluck('addon_id');
            $addons = Addon::whereIn('id', $addonIds)->get()->keyBy('id');

            foreach ($data['addons'] as $addon) {
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
        $discount = 0;
        $validZero = false;
        $finalTotal = $subtotal;

        if (!empty($data['offer_id'])) {
            $offer = Offer::find($data['offer_id']);
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
                        return ['error' => '⚠️ Discount too high! Total is $0.00. Please review the offer.'];
                    }

                    $finalTotal = 0;
                }
            }
        }

        // Update booking
        
        $data = $this->addUpdatedBy($data);

        $booking->update([
            'customer_id' => $data['customer_id'],
            'appointment_time' => $data['appointment_time'],
            'offer_id' => $data['offer_id'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => $data['status'],
            'payment_status' => $data['payment_status'],
            'payment_method' => $data['payment_method'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $finalTotal,
            'addon_amount' => $addonTotal,
            'service_variant_amount' => $serviceTotal,
        ]);

        // Sync services
        $serviceSync = [];
        foreach ($serviceData as $service) {
            $serviceSync[$service['service_variant_id']] = [
                'price' => $service['price'],
                'staff_id' => $service['staff_id'] ?? null,
                'status' => 'pending',
                'updated_at' => now(),
            ];
        }
        $booking->serviceVariants()->sync($serviceSync);

        // Sync addons
        $addonSync = [];
        foreach ($addonData as $addon) {
            $addonSync[$addon['addon_id']] = [
                'price' => $addon['price'],
                'staff_id' => $addon['staff_id'] ?? null,
                'status' => 'pending',
            ];
        }
        $booking->addons()->sync($addonSync);

        return $booking;
    }



    public function delete($id)
    {
        $booking = Booking::findOrFail($id);
        return $booking->delete(); // uses softDeletes
    }

    public function toggleStatus($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = $booking->status === 'active' ? 'inactive' : 'active';
        $booking->save();

        return $booking;
    }

    public function bulkDelete(array $ids)
    {
        return Booking::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Booking::whereIn('id', $ids)->update(['status' => $status]);
    }
}
