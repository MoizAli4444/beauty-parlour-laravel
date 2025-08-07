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
                            <h5 class="mb-0">Create Addon</h5>
                            <a href="{{ route('addons.index') }}" class="btn btn-warning">All Addons</a>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf

                                <!-- User -->
                                <div class="mb-4">
                                    <label class="form-label" for="user_id">Select User</label>
                                    <select name="user_id" class="form-select" id="user_id" required>
                                        <option value="">-- Choose Customer --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Service Variant -->
                                <div class="mb-4">
                                    <label class="form-label" for="service_variant_id">Select Service Variant</label>
                                    <select name="service_variant_id" class="form-select" id="service_variant_id" required>
                                        <option value="">-- Choose Service --</option>
                                        @foreach ($variants as $variant)
                                            <option value="{{ $variant->id }}"
                                                {{ old('service_variant_id') == $variant->id ? 'selected' : '' }}>
                                                {{ $variant->name }} - ${{ number_format($variant->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Appointment Time -->
                                <div class="mb-4">
                                    <label class="form-label" for="appointment_time">Appointment Time</label>
                                    <input type="datetime-local" name="appointment_time" id="appointment_time"
                                        class="form-control" value="{{ old('appointment_time') }}" required>
                                </div>

                                <!-- Addons (multiple checkboxes) -->
                                <div class="mb-4">
                                    <label class="form-label">Addons</label>
                                    <div class="row">
                                        @foreach ($addons as $addon)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="addons[]"
                                                        value="{{ $addon->id }}" id="addon_{{ $addon->id }}"
                                                        {{ is_array(old('addons')) && in_array($addon->id, old('addons')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="addon_{{ $addon->id }}">
                                                        {{ $addon->name }} (+${{ $addon->price }})
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- <!-- Tip Amount -->
                                <div class="mb-4">
                                    <label class="form-label" for="tip_amount">Tip Amount</label>
                                    <input type="number" name="tip_amount" step="0.01" class="form-control"
                                        value="{{ old('tip_amount', 0) }}" placeholder="Optional">
                                </div> --}}

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label" for="status">Booking Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Pending</option>
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Confirmed
                                        </option>
                                    </select>
                                </div>

                                <!-- Payment Status -->
                                <div class="mb-4">
                                    <label class="form-label" for="payment_status">Payment Status</label>
                                    <select name="payment_status" id="payment_status" class="form-select">
                                        <option value="0" {{ old('payment_status') == 0 ? 'selected' : '' }}>Unpaid
                                        </option>
                                        <option value="1" {{ old('payment_status') == 1 ? 'selected' : '' }}>Paid
                                        </option>
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Create Booking</button>
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
