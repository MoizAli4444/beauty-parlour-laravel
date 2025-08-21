@extends('admin.layouts.app')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xl">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Booking</h5>
                            <div>

                                {{-- {!! render_delete_button($booking->id, route('bookings.destroy', $booking->id), false) !!} --}}
                                {!! render_edit_button(route('bookings.edit', $booking->id), false) !!}
                                {!! render_index_button(route('bookings.index'), 'All Bookings', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <!-- Booking Info -->
                                <div class="col-12 col-lg-8">
                                    <div class="card shadow-sm rounded-3">
                                        <div class="card-header bg-light text-white py-2">
                                            <h5 class="mb-0 ">Booking Details #{{ $booking->id }}</h5>
                                        </div>
                                        <div class="card-body py-2">

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Customer:</strong>
                                                    <p class="mb-0">{{ $booking->customer?->user?->name ?? 'Unknown' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Appointment Time:</strong>
                                                    <p class="mb-0">
                                                        {{ $booking->appointment_time->format('d M Y, h:i A') }}</p>
                                                </div>
                                            </div>

                                            @if ($booking->offer)
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Offer Applied:</strong>
                                                        <p class="mb-0">{{ $booking->offer->name }}
                                                            ({{ $booking->offer->offer_code }})</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Discount:</strong>
                                                        <p class="mb-0">
                                                            {{ $booking->offer->type == 'percentage' ? $booking->offer->value . '%' : 'Rs ' . number_format($booking->offer->value, 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($booking->note)
                                                <div class="mb-3">
                                                    <strong>Note:</strong>
                                                    <p class="mb-0 text-muted">{{ $booking->note }}</p>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <!-- Services Table -->
                                    <div class="card shadow-sm rounded-3 mt-4">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="mb-0">Services</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Service</th>
                                                            <th>Staff</th>
                                                            <th>Price (Rs)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($booking->serviceVariants as $service)
                                                            <tr>
                                                                <td>{{ $service->name }}</td>
                                                                <td>{{ $service->pivot->staff?->user?->name ?? 'N/A' }}</td>
                                                                <td>{{ number_format($service->pivot->price, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Addons Table -->
                                    <div class="card shadow-sm rounded-3 mt-4">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="mb-0">Addons</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Addon</th>
                                                            <th>Staff</th>
                                                            <th>Price (Rs)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($booking->addons as $addon)
                                                            <tr>
                                                                <td>{{ $addon->name }}</td>
                                                                <td>{{ $addon->pivot->staff?->user?->name ?? 'N/A' }}</td>
                                                                <td>{{ number_format($addon->pivot->price, 2) }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">No addons
                                                                    selected</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary & Status -->
                                <div class="col-12 col-lg-4">
                                    <div class="card shadow-sm rounded-3">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="mb-0">Booking Summary</h6>
                                        </div>
                                        <div class="card-body">

                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong>Services Total:</strong>
                                                    <span>Rs
                                                        {{ number_format($booking->service_variant_amount, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong>Addons Total:</strong>
                                                    <span>Rs {{ number_format($booking->addon_amount, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong>Subtotal:</strong>
                                                    <span>Rs {{ number_format($booking->subtotal, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between text-success">
                                                    <strong>Discount:</strong>
                                                    <span>Rs {{ number_format($booking->discount, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between border-top mt-2">
                                                    <strong>Total:</strong>
                                                    <strong>Rs {{ number_format($booking->total_amount, 2) }}</strong>
                                                </li>
                                            </ul>

                                            <hr>

                                            <div class="d-flex justify-content-between mb-2">
                                                <strong>Status:</strong>
                                                <span
                                                    class="badge 
                                                    @if ($booking->status == 'pending') bg-warning 
                                                    @elseif($booking->status == 'confirmed') bg-primary 
                                                    @elseif($booking->status == 'completed') bg-success 
                                                    @elseif($booking->status == 'cancelled') bg-secondary 
                                                    @elseif($booking->status == 'rejected') bg-danger @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <strong>Payment Status:</strong>
                                                <span
                                                    class="badge {{ $booking->payment_status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $booking->payment_status ? 'Paid' : 'Unpaid' }}
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <strong>Payment Method:</strong>
                                                <span>{{ ucfirst($booking->payment_method) }}</span>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>





                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->
        </div>
    @endsection
