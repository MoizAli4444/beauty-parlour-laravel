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

                            @include('admin.pages-partials.bulk-actions', [
                                'itemType' => 'services',
                                'actions' => [
                                    [
                                        'text' => 'Delete Selected',
                                        'value' => 'delete',
                                        'class' => 'btn-danger',
                                        'url' => route('services.bulkDelete'),
                                    ],
                                    [
                                        'text' => 'Mark as Active',
                                        'value' => 'active',
                                        'class' => 'btn-success',
                                        'url' => route('services.bulkStatus'),
                                    ],
                                    [
                                        'text' => 'Mark as Inactive',
                                        'value' => 'inactive',
                                        'class' => 'btn-warning',
                                        'url' => route('services.bulkStatus'),
                                    ],
                                ],
                            ])

                            <table id="indexPageDataTable" class="table table-bordered">
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
