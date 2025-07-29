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
                            <h5 class="mb-0">View Addon</h5>
                            <div>

                                {!! render_delete_button($addon->id, route('addons.destroy', $addon->id), false) !!}
                                {!! render_edit_button(route('addons.edit', $addon->slug), false) !!}
                                {!! render_index_button(route('addons.index'), 'All Addons', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Addon Name -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Addon Name:</label>
                                        <div>{{ $addon->name }}</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description:</label>
                                        <div>{{ $addon->description ?? '-' }}</div>
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Price (PKR):</label>
                                        <div>Rs {{ number_format($addon->price, 2) }}</div>
                                    </div>

                                    <!-  dd - Duration -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Duration (minutes):</label>
                                        <div>{{ $addon->duration ?? '-' }}</div>
                                    </div>

                                    <!-- Gender -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Gender:</label>
                                        <div>{{ $addon->gender->label() ?? '-' }}</div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Status:</label>
                                        <div>{!! $addon->status_badge !!}</div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Addon Image:</label><br>

                                    @if (!empty($addon->image) && file_exists(public_path('storage/' . $addon->image)))
                                        <img src="{{ asset('storage/' . $addon->image) }}" alt="Addon Image"
                                            class="rounded shadow-sm" style="width: 250px; object-fit: cover;">
                                    @else
                                        <div class="text-muted">No image uploaded</div>
                                    @endif
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
