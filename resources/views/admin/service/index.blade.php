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
                            <h5 class="mb-0">All Services</h5>
                            <a href="{{ route('services.create') }}" class="btn btn-warning">Create</a>
                        </div>
                        <div class="card-body">
                            <button id="bulkDelete" class="btn btn-danger btn-sm btn-bulk-actions"
                                data-url="{{ route('services.bulkDelete') }}" data-action="delete"
                                data-message="Are you sure you want to delete selected services?">
                                Delete Selected
                            </button>

                            <button id="bulkActivate" class="btn btn-success btn-sm btn-bulk-actions"
                                data-url="{{ route('services.bulkStatus') }}" data-action="active"
                                data-message="Are you sure you want to activate selected services?">
                                Mark Active
                            </button>

                            <button id="bulkDeactivate" class="btn btn-warning btn-sm btn-bulk-actions"
                                data-url="{{ route('services.bulkStatus') }}" data-action="inactive"
                                data-message="Are you sure you want to deactivate selected services?">
                                Mark Inactive
                            </button>



                            <table id="servicesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th> {{-- universal checkbox --}}
                                        <th>ID</th>
                                        <th>Name</th>
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
        <!-- / Content -->


        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
@endsection

@push('scripts')
    @include('admin.service.js.script')
@endpush

{{-- @section('scripts')
    @include('admin.service.js.script')
@endpush --}}
