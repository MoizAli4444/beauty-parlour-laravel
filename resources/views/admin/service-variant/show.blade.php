@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mb-6 gy-6">
            <div class="col-xl">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">View Service Variant</h5>
                        <div>
                            <a href="{{ route('service-variants.index') }}" class="btn btn-warning">All Variants</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Variant Name:</label>
                                    <div>{{ $variant->name }}</div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Service:</label>
                                    <div>{{ $variant->service->name ?? '-' }}</div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Price:</label>
                                    <div>{{ number_format($variant->price, 2) }}</div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Status:</label>
                                    <div>{!! $variant->status_badge !!}</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Variant Image:</label><br>
                                @if (!empty($variant->image) && file_exists(public_path('storage/' . $variant->image)))
                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="Variant Image"
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
</div>
@endsection
