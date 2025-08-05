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
                            <h5 class="mb-0">All Offers</h5>
                            <a href="{{ route('offers.create') }}" class="btn btn-warning">Create</a>
                        </div>
                        <div class="card-body">

                            @include('admin.pages-partials.bulk-actions', [
                                'deleteUrl' => route('offers.bulkDelete'),
                                'statusUrl' => route('offers.bulkStatus'),
                                'itemType' => 'offers', // optional
                            ])

                            <div class="table-responsive">
                                <table id="indexPageDataTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th> {{-- universal checkbox --}}

                                            <th>ID</th>
                                            <th>Offer Name</th>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Code</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>

                                            <th>Status</th>
                                            <th>Actions</th>
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


        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
@endsection

@push('scripts')
    @include('admin.offer.js.script')
@endpush
