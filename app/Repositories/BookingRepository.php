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
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingRepository implements BookingRepositoryInterface
{
    use TracksUser;

    public function getDatatableData_old()
    {
        try {
            return DataTables::of(
                Booking::with(['customer.user', 'offer'])->latest()
            )

                // ✅ Checkbox column
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                // ✅ ID
                ->addColumn('id', function ($row) {
                    return $row->id;
                })

                // ✅ Customer Name
                ->addColumn('customer_user_name', function ($row) {
                    return $row->customer && $row->customer->user
                        ? $row->customer->user->name
                        : 'N/A';
                })

                // ✅ Appointment Time (formatted)
                ->addColumn('appointment_time', function ($row) {
                    return $row->appointment_time
                        ? Carbon::parse($row->appointment_time)->format('d M Y H:i')
                        : 'N/A';
                })

                // ✅ Total Amount (with currency symbol)
                ->addColumn('total_amount', function ($row) {
                    return 'Rs ' . number_format($row->total_amount, 2);
                })

                // ✅ Status (badge)
                ->editColumn('status', function ($row) {
                    return $row->status_badge; // uses model accessor
                })

                // ✅ Payment Status (badge)
                ->addColumn('payment_status_badge', function ($row) {
                    if ($row->payment_status == 1) {
                        return '<span class="badge bg-success">Paid</span>';
                    }
                    return '<span class="badge bg-danger">Unpaid</span>';
                })

                // ✅ Payment Method
                ->addColumn('payment_method', function ($row) {
                    return $row->payment_method ? ucfirst($row->payment_method) : 'N/A';
                })

                // ✅ Created At (formatted)
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })

                // ✅ Action Buttons
                ->addColumn('action', function ($row) {
                    return view('admin.booking.action', ['booking' => $row])->render();
                })

                ->rawColumns([
                    'checkbox',
                    'payment_status_badge',
                    'action',
                    'status'
                ]) // Allow HTML badges and buttons
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDatatableData(array $filters)
    {
        try {
            $query = Booking::with(['customer.user', 'offer'])->latest();

            // ✅ Filters
            // Apply filters dynamically
            if (!empty($filters['customer_id'])) {
                $query->where('customer_id', $filters['customer_id']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if ($filters['payment_status'] !== null && $filters['payment_status'] !== '') {
                $query->where('payment_status', $filters['payment_status']);
            }

            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }

            return DataTables::of($query)

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn('customer_user_name', function ($row) {
                    return $row->customer && $row->customer->user
                        ? $row->customer->user->name
                        : 'N/A';
                })

                ->addColumn('appointment_time', function ($row) {
                    return $row->appointment_time
                        ? Carbon::parse($row->appointment_time)->format('d M Y H:i')
                        : 'N/A';
                })

                ->addColumn('total_amount', fn($row) => 'Rs ' . number_format($row->total_amount, 2))

                ->editColumn('status', fn($row) => $row->status_badge)

                ->addColumn('payment_status_badge', function ($row) {
                    return $row->payment_status == 1
                        ? '<span class="badge bg-success">Paid</span>'
                        : '<span class="badge bg-danger">Unpaid</span>';
                })

                ->addColumn('payment_method', fn($row) => $row->payment_method ? ucfirst($row->payment_method) : 'N/A')

                ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))

                ->addColumn('action', fn($row) => view('admin.booking.action', ['booking' => $row])->render())

                ->rawColumns(['checkbox', 'payment_status_badge', 'action', 'status'])
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
        // if (!empty($data['services'])) {
        $variantIds = collect($data['services'])->pluck('service_variant_id');
        $variants = ServiceVariant::whereIn('id', $variantIds)->get()->keyBy('id');


        $addonTotal = 0;
        $serviceTotal = 0;
        $serviceData = [];

        if (!empty($variants)) {
            foreach ($data['services'] as $service) {
                if (isset($variants[$service['service_variant_id']])) {
                    $variant = $variants[$service['service_variant_id']];
                    $price = $variant->price;
                    $serviceTotal += $price;

                    $serviceData[] = [
                        'service_variant_id' => $variant->id,
                        'price' => $price,
                        'staff_id' => $service['staff_id'] ?? null,
                    ];
                }
            }
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

        if (!empty($variants)) {
            foreach ($data['services'] as $service) {
                if (isset($variants[$service['service_variant_id']])) {
                    $variant = $variants[$service['service_variant_id']];
                    $price = $variant->price;
                    $serviceTotal += $price;

                    $serviceData[] = [
                        'service_variant_id' => $variant->id,
                        'price' => $price,
                        'staff_id' => $service['staff_id'] ?? null,
                    ];
                }
            }
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
