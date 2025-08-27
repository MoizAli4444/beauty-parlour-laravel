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
                            <h5 class="mb-0">All Booking Reviews</h5>
                        </div>
                        <div class="card-body">

                            {{-- ✅ Include Filter Partial --}}
                            @include('admin.booking-reviews.partials.filters')


                            @include('admin.pages-partials.bulk-actions', [
                                'statusUrl' => route('booking-reviews.bulkStatus'),
                                'itemType' => 'bookings', // optional
                            ])

                            <div class="table-responsive">
                                <table id="indexPageDataTable" class="table table-bordered">
                                    <thead>

                                        <tr>
                                            <th>
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>Booking</th>
                                            <th>Rating</th>
                                            <th>Review</th>
                                            <th>Status</th>
                                            <th>Moderator</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->

        @include('admin.booking-reviews.partials.changestatus_modal')


        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
@endsection

@push('scripts')
    @include('admin.booking-reviews.js.index')
@endpush
