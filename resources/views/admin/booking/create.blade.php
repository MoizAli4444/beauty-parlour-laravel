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
                            <h5 class="mb-0">Create Booking</h5>
                            <a href="{{ route('bookings.index') }}" class="btn btn-warning">All Bookings</a>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Customer -->
                                    <div class="mb-3 col-md-4">
                                        <label for="user_id">Customer</label>
                                        <select name="user_id" class="form-control" required>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Appointment Time -->
                                    <div class="mb-3 col-md-4">
                                        <label for="appointment_time">Appointment Time</label>
                                        <input type="datetime-local" name="appointment_time" class="form-control" required>
                                    </div>

                                    <!-- Offer -->
                                    <div class="mb-3 col-md-4">
                                        <label for="offer_id">Offer (optional)</label>
                                        <select name="offer_id" class="form-control">
                                            <option value="">-- None --</option>
                                            @foreach ($offers as $offer)
                                                {{-- <option value="{{ $offer->id }}" data-discount="{{ $offer->discount }}">
                                                {{ $offer->name }} ({{ $offer->offer_code }}) - Discount:
                                                ${{ $offer->discount }}
                                            </option> --}}
                                                <option value="{{ $offer->id }}" data-discount="{{ $offer->value }}"
                                                    data-type="{{ $offer->type }}">
                                                    {{ $offer->name }} ({{ $offer->offer_code }})
                                                    -
                                                    {{ $offer->type == 'percentage' ? $offer->value . '%' : '$' . $offer->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Add Service Variants -->
                                    <div class="mb-3 col-12">
                                        <label>Services</label>
                                        <div id="service-container">
                                            <div class="row mb-2 service-row">
                                                <div class="col-md-6">
                                                    <select name="services[0][service_variant_id]" class="form-control"
                                                        required>
                                                        <option value="">Select Service</option>
                                                        @foreach ($serviceVariants as $variant)
                                                            {{-- <option value="{{ $variant->id }}">{{ $variant->name }}
                                                            </option> --}}
                                                            <option value="{{ $variant->id }}"
                                                                data-price="{{ $variant->price }}">{{ $variant->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" readonly disabled name="services[0][price]"
                                                        placeholder="Price" class="form-control" step="0.01" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="services[0][staff_id]" class="form-control">
                                                        <option value="">Select Staff</option>
                                                        @foreach ($staff as $employee)
                                                            <option value="{{ $employee->id }}">{{ $employee->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-service-btn">&times;</button>
                                                </div>
                                            </div>
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
                                                <div class="row mb-2 align-items-center">
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="addons[{{ $index }}][addon_id]"
                                                                value="{{ $addon->id }}"
                                                                id="addon_{{ $addon->id }}">
                                                            <label class="form-check-label"
                                                                for="addon_{{ $addon->id }}">
                                                                {{ $addon->name }} (+${{ $addon->price }})
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="number" name="addons[{{ $index }}][price]"
                                                            placeholder="Custom Price (optional)" class="form-control"
                                                            step="0.01" value="{{ $addon->price }}" readonly disabled>

                                                    </div>

                                                    <div class="col-md-4">
                                                        <select name="addons[{{ $index }}][staff_id]"
                                                            class="form-control">
                                                            <option value="">Select Staff</option>
                                                            @foreach ($staff as $employee)
                                                                <option value="{{ $employee->id }}">{{ $employee->name }}
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
                                        <textarea name="note" class="form-control" rows="3" placeholder="Optional note..."></textarea>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-3 col-12 col-md-4">
                                        <label class="form-label" for="status">Booking Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Confirmed
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Payment Status -->
                                    <div class="mb-3 col-12 col-md-4">
                                        <label class="form-label" for="payment_status">Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-select">
                                            <option value="0" {{ old('payment_status') == 0 ? 'selected' : '' }}>
                                                Unpaid
                                            </option>
                                            <option value="1" {{ old('payment_status') == 1 ? 'selected' : '' }}>Paid
                                            </option>
                                        </select>
                                    </div>


                                    <!-- Payment Method -->
                                    <div class="mb-3 col-12 col-md-4">
                                        <label for="payment_method">Payment Method</label>
                                        <select name="payment_method" class="form-control" required>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="wallet">Wallet</option>
                                            <option value="online">Online</option>
                                        </select>
                                    </div>



                                    <div class="col-12 mt-4 border-top pt-3">
                                        {{-- <div id="discount-info" class="mt-2 text-success"></div>
                                        <div id="error-message" class="text-danger mt-2"></div>

                                        <h5>Booking Summary</h5>
                                        <p><strong>Services Total:</strong> $<span id="services-total">0.00</span></p>
                                        <p><strong>Addons Total:</strong> $<span id="addons-total">0.00</span></p>
                                        <p><strong>Subtotal:</strong> $<span id="subtotal">0.00</span></p>
                                        <p><strong>Offer Discount:</strong> -$<span id="offer-discount">0.00</span></p>
                                        <hr>
                                        <p><strong>Total After Discount:</strong> $<span id="final-total">0.00</span></p> --}}

                                        {{-- <div>Services Total: $<span id="services-total">0.00</span></div>
                                        <div>Addons Total: $<span id="addons-total">0.00</span></div>
                                        <div>Subtotal: $<span id="subtotal">0.00</span></div>
                                        <div id="discount-info"></div>
                                        <div>Discount: $<span id="offer-discount">0.00</span></div>
                                        <div id="error-message" class="text-danger"></div>
                                        <div>Total After Discount: $<span id="final-total">0.00</span></div> --}}

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
                                                <span>$<span id="services-total">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Addons Total:</strong>
                                                <span>$<span id="addons-total">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Subtotal:</strong>
                                                <span>$<span id="subtotal">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between text-success">
                                                <strong>Offer Discount:</strong>
                                                <span>-$<span id="offer-discount">0.00</span></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between border-top mt-2">
                                                <strong>Total After Discount:</strong>
                                                <strong>$<span id="final-total">0.00</span></strong>
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

@push('scripts')
    @include('admin.booking.js.script')
@endpush
