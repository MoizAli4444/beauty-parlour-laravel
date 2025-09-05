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
                            <h5 class="mb-0">All Deals</h5>
                            <a href="{{ route('deals.create') }}" class="btn btn-warning">Create</a>
                        </div>
                        <div class="card-body">

                            @include('admin.pages-partials.bulk-actions', [
                                'itemType' => 'deals',
                                'actions' => [
                                    [
                                        'text' => 'Delete Selected',
                                        'value' => 'delete',
                                        'class' => 'btn-danger',
                                        'url' => route('deals.bulkDelete'),
                                    ],
                                    [
                                        'text' => 'Mark as Active',
                                        'value' => 'active',
                                        'class' => 'btn-success',
                                        'url' => route('deals.bulkStatus'),
                                    ],
                                    [
                                        'text' => 'Mark as Inactive',
                                        'value' => 'inactive',
                                        'class' => 'btn-secondary',
                                        'url' => route('deals.bulkStatus'),
                                    ],
                                ],
                            ])


                            <div class="table-responsive">
                                <table id="dealsDataTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th> {{-- universal checkbox --}}
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Price</th>
                                            <th>Validity</th> {{-- start_date - end_date --}}
                                            <th>Status</th>
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

        <div class="content-backdrop fade"></div>
    </div>

    <!-- Media Preview Modal -->
    @include('admin.pages-partials.preview_modal')





    <!-- Content wrapper -->
@endsection

@push('scripts')
    @include('admin.deals.js.script')
@endpush
