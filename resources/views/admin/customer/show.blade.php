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
                            <h5 class="mb-0">View Service</h5>
                            <div>

                                {!! render_delete_button($service->id, route('services.destroy', $service->id), false) !!}
                                {!! render_edit_button(route('services.edit', $service->slug), false) !!}
                                {!! render_index_button(route('services.index'), 'All Services', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Left Column: Details -->
                                <div class="col-md-8">
                                    <!-- Service Name -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Service Name:</label>
                                        <div>{{ $service->name }}</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description:</label>
                                        <div>{{ $service->description ?? '-' }}</div>
                                    </div>

                                    <!-- Status & Created At Row -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Status:</label>
                                            <div>{!! $service->status_badge !!}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Created At:</label>
                                            <div>{{ $service->created_at->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Image -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Service Image:</label><br>
                                    @if (!empty($service->image) && file_exists(public_path('storage/' . $service->image)))
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="Service Image"
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
