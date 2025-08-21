@extends('admin.layouts.app')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Basic Layout -->
            <div class="row mb-6 gy-6">
                <div class="col-xl">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edit Addon</h5>

                            <div>
                                {!! render_delete_button($addon->id, route('addons.destroy', $addon->id), false) !!}
                                {!! render_view_button(route('addons.show', $addon->slug), false) !!}
                                {!! render_index_button(route('addons.index'), 'All Addons', false) !!}

                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('bookings.update') }}" method="POST">

                                @csrf

                                <div class="row">
                                    <!-- Customer -->
                                    <div class="mb-3 col-md-4">
                                        <label for="customer_id">Customer</label>
                                        <select name="customer_id" class="form-control" required>
                                            <option value="">Select Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id', $booking->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->user?->name ?? 'Unknown' }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <!-- Appointment Time -->
                                    <div class="mb-3 col-md-4">
                                        <label for="appointment_time">Appointment Time</label>
                                        <input type="datetime-local" name="appointment_time" class="form-control" required
                                            value="{{ old('appointment_time', $booking->appointment_time ? \Carbon\Carbon::parse($booking->appointment_time)->format('Y-m-d\TH:i') : '') }}">

                                    </div>

                                    <!-- Offer -->
                                    <div class="mb-3 col-md-4">
                                        <label for="offer_id">Offer (optional)</label>
                                        <select name="offer_id" class="form-control">
                                            <option value="">-- None --</option>
                                            @foreach ($offers as $offer)
                                                <option value="{{ $offer->id }}"
                                                    {{ old('offer_id', $booking->offer_id) == $offer->id ? 'selected' : '' }}
                                                    data-discount="{{ $offer->value }}" data-type="{{ $offer->type }}">
                                                    {{ $offer->name }}
                                                    @if ($offer->offer_code)
                                                        ({{ $offer->offer_code }})
                                                    @endif -
                                                    {{ $offer->type == 'percentage' ? $offer->value . '%' : 'Rs ' . $offer->value }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <!-- Add Service Variants -->
                                    <div class="mb-3 col-12">
                                        <label>Services</label>
                                        <div id="service-container">
                                            @foreach ($booking->serviceVariants as $index => $variant)
                                                <div class="row mb-2 service-row">
                                                    <div class="col-md-6">
                                                        <select name="services[{{ $index }}][service_variant_id]"
                                                            class="form-control">
                                                            <option value="">Select Service</option>
                                                            @foreach ($serviceVariants as $sv)
                                                                <option value="{{ $sv->id }}"
                                                                    {{ old("services.$index.service_variant_id", $variant->id) == $sv->id ? 'selected' : '' }}
                                                                    data-price="{{ $sv->price }}">
                                                                    {{ $sv->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="services[{{ $index }}][price]"
                                                            value="{{ old("services.$index.price", $variant->pivot->price) }}"
                                                            placeholder="Price" class="form-control" step="0.01"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select name="services[{{ $index }}][staff_id]"
                                                            class="form-control">
                                                            <option value="">Select Staff</option>
                                                            @foreach ($staffMembers as $staff)
                                                                <option value="{{ $staff->id }}"
                                                                    {{ old("services.$index.staff_id", $variant->pivot->staff_id) == $staff->id ? 'selected' : '' }}>
                                                                    {{ $staff->user?->name ?? 'Unknown' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-center">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-service-btn">&times;</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button" id="addServiceBtn"
                                            class="btn btn-sm btn-outline-primary mt-2">Add Service</button>
                                    </div>

                                    <!-- Add Addons -->

                                    <div class="mb-3 col-12">
                                        <label>Addons</label>
                                        <div
                                            style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                            @foreach ($addons as $index => $addon)
                                                @php
                                                    $selectedAddon = $booking->addons->firstWhere('id', $addon->id);
                                                @endphp
                                                <div class="row mb-2 align-items-center">
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="addons[{{ $index }}][addon_id]"
                                                                value="{{ $addon->id }}" id="addon_{{ $addon->id }}"
                                                                {{ $selectedAddon ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="addon_{{ $addon->id }}">
                                                                {{ $addon->name }} (+Rs{{ $addon->price }})
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="addons[{{ $index }}][price]"
                                                            value="{{ old("addons.$index.price", $selectedAddon?->pivot->price ?? $addon->price) }}"
                                                            class="form-control" step="0.01" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="addons[{{ $index }}][staff_id]"
                                                            class="form-control">
                                                            <option value="">Select Staff</option>
                                                            @foreach ($staffMembers as $staff)
                                                                <option value="{{ $staff->id }}"
                                                                    {{ old("addons.$index.staff_id", $selectedAddon?->pivot->staff_id) == $staff->id ? 'selected' : '' }}>
                                                                    {{ $staff->user?->name ?? 'Unknown' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach


                                        </div>
                                    </div>


                                    <!-- Note -->
                                    <div class="mb-3 col-12">
                                        <label for="note">Note</label>
                                        <textarea name="note" class="form-control" rows="3">{{ old('note', $booking->note) }}</textarea>

                                    </div>

                                    <!-- Status -->
                                    <div class="mb-3 col-12 col-md-4">
                                        <label class="form-label" for="status">Booking Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="pending"
                                                {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="confirmed"
                                                {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>
                                                Confirmed</option>
                                        </select>

                                    </div>

                                    <!-- Payment Status -->
                                    <div class="mb-3 col-12 col-md-4">
                                        <label class="form-label" for="payment_status">Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-select">
                                            <option value="0"
                                                {{ old('payment_status', $booking->payment_status) == 0 ? 'selected' : '' }}>
                                                Unpaid</option>
                                            <option value="1"
                                                {{ old('payment_status', $booking->payment_status) == 1 ? 'selected' : '' }}>
                                                Paid</option>
                                        </select>

                                    </div>


                                    <!-- Payment Method -->
                                    <div class="mb-3 col-12 col-md-4">
                                        <label for="payment_method">Payment Method</label>
                                        <select name="payment_method" class="form-control" required>
                                            <option value="cash"
                                                {{ old('payment_method', $booking->payment_method) == 'cash' ? 'selected' : '' }}>
                                                Cash</option>
                                            <option value="card"
                                                {{ old('payment_method', $booking->payment_method) == 'card' ? 'selected' : '' }}>
                                                Card</option>
                                            <option value="wallet"
                                                {{ old('payment_method', $booking->payment_method) == 'wallet' ? 'selected' : '' }}>
                                                Wallet</option>
                                            <option value="online"
                                                {{ old('payment_method', $booking->payment_method) == 'online' ? 'selected' : '' }}>
                                                Online</option>
                                        </select>

                                    </div>





                                    <div class="col-12 mt-4 border-top pt-3">
                                        {{-- Discount Alert/Error Section --}}
                                        <div id="discount-info" class="alert alert-success d-none"></div>
                                        <div id="error-message" class="alert alert-danger d-none"></div>

                                        {{-- Booking Summary --}}
                                        <h5 class="mb-3">Booking Summary</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Services Total:</strong>
                                                <span>Rs <span id="services-total">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Addons Total:</strong>
                                                <span>Rs <span id="addons-total">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Subtotal:</strong>
                                                <span>Rs <span id="subtotal">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between text-success">
                                                <strong>Offer Discount:</strong>
                                                <span>Rs <span id="offer-discount">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between border-top mt-2">
                                                <strong>Total After Discount:</strong>
                                                <strong>Rs <span id="final-total">0.00</span></strong>
                                            </li>
                                        </ul>
                                    </div>


                                    <!-- Submit -->
                                    <div class="mb-3 col-12">
                                        <button type="submit" class="btn btn-success">Create Booking</button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->

    </div>
    <!-- Content wrapper -->
@endsection
