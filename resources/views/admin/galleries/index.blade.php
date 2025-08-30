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
                            <h5 class="mb-0">All Galleries</h5>
                            <a href="{{ route('galleries.create') }}" class="btn btn-warning">Create</a>
                        </div>
                        <div class="card-body">

                            @include('admin.pages-partials.bulk-actions', [
                                'deleteUrl' => route('galleries.bulkDelete'),
                                'statusUrl' => route('galleries.bulkStatus'),
                                'itemType' => 'galleries', // optional
                            ])

                            <div class="table-responsive">
                                <table id="indexPageDataTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th> {{-- universal checkbox --}}
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Media</th>
                                            <th>Media Type</th>
                                            <th>Featured</th>
                                            <th>Alt Text</th>
                                            <th>File Size</th>
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
    @include('admin.galleries.js.script')
@endpush
