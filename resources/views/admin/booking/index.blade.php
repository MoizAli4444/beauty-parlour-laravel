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

                            <form method="GET" class="mb-3">
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->user?->name ?? 'Unknown' }}</option>
                                    @endforeach
                                </select>
                                <select name="status">
                                    <option value="">All Status</option>
                                    @foreach ($booking_statuses as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ request('status') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>


                                <select name="payment_status">
                                    <option value="">All Payments</option>
                                    <option value="0" {{ request('payment_status') === '0' ? 'selected' : '' }}>Unpaid
                                    </option>
                                    <option value="1" {{ request('payment_status') === '1' ? 'selected' : '' }}>Paid
                                    </option>
                                </select>
                                <input type="date" name="date_from" value="{{ request('date_from') }}">
                                <input type="date" name="date_to" value="{{ request('date_to') }}">
                                <button type="submit">Filter</button>
                            </form>


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
