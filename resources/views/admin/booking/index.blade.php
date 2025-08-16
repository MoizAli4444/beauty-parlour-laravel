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
                            <h5 class="mb-0">All Addons</h5>
                            <a href="{{ route('addons.create') }}" class="btn btn-warning">Create</a>
                        </div>
                        <div class="card-body">

                          <div class="card mb-4 border-0 shadow-lg rounded-3">
    <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center rounded-top border-bottom" 
         style="background: linear-gradient(135deg, #4e73df, #224abe);">
        <h6 class="mb-0 fw-bold">
            <i class="bi bi-funnel me-2"></i> Booking Filters
        </h6>
        <button class="btn btn-sm btn-light text-primary fw-semibold shadow-sm" type="button"
            data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true">
            <i class="bi bi-sliders me-1"></i> Toggle Filters
        </button>
    </div>


                                <div class="collapse mt-2" id="filterCollapse">
                                    <div class="card-body">
                                        <form id="filterForm" class="row g-3">
                                            <!-- Customer -->
                                            <div class="col-md-3">
                                                <label class="form-label">Customer</label>
                                                <select name="customer_id" class="form-select">
                                                    <option value="">All Customers</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">
                                                            {{ $customer->user?->name ?? 'Unknown' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Status -->
                                            <div class="col-md-2">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="">All</option>
                                                    @foreach ($booking_statuses as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Payment Status -->
                                            <div class="col-md-2">
                                                <label class="form-label">Payment</label>
                                                <select name="payment_status" class="form-select">
                                                    <option value="">All</option>
                                                    <option value="0">Unpaid</option>
                                                    <option value="1">Paid</option>
                                                </select>
                                            </div>

                                            <!-- Date From -->
                                            <div class="col-md-2">
                                                <label class="form-label">From</label>
                                                <input type="date" name="date_from" class="form-control">
                                            </div>

                                            <!-- Date To -->
                                            <div class="col-md-2">
                                                <label class="form-label">To</label>
                                                <input type="date" name="date_to" class="form-control">
                                            </div>

                                            <!-- Buttons -->
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-2">
                                                    <i class="bi bi-search"></i> Apply
                                                </button>
                                                <button type="button" id="resetFilter" class="btn btn-outline-secondary">
                                                    <i class="bi bi-arrow-repeat"></i> Reset
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            @include('admin.pages-partials.bulk-actions', [
                                'deleteUrl' => route('addons.bulkDelete'),
                                'statusUrl' => route('addons.bulkStatus'),
                                'itemType' => 'addons', // optional
                            ])

                            <table id="indexPageDataTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th> {{-- universal checkbox --}}
                                        <th>ID</th>
                                        <th>Customer Name</th>
                                        <th>Appointment Time</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Payment Method</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->


        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
@endsection

@push('scripts')
    @include('admin.booking.js.index')
@endpush
