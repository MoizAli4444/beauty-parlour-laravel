@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">Service Variant Detail</h4>
                <a href="{{ route('service-variants.index') }}" class="btn btn-secondary">Back to Variants</a>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">

                        <div class="mb-3">
                            <strong>Name:</strong>
                            <div>{{ $variant->name }}</div>
                        </div>

                        <div class="mb-3">
                            <strong>Description:</strong>
                            <div>{!! nl2br(e($variant->description)) ?? 'N/A' !!}</div>
                        </div>

                        <div class="mb-3">
                            <strong>Price:</strong>
                            <div>{{ number_format($variant->price, 2) }}</div>
                        </div>

                        <div class="mb-3">
                            <strong>Status:</strong>
                            <div>{!! $variant->status_badge ?? 'N/A' !!}</div>
                        </div>

                        <div class="mb-3">
                            <strong>Belongs to Service:</strong>
                            <div>{{ $variant->service->name ?? 'N/A' }}</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Image -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Image</strong>
                    </div>
                    <div class="card-body text-center">
                        @if (!empty($variant->image) && file_exists(public_path('storage/' . $variant->image)))
                            <img src="{{ asset('storage/' . $variant->image) }}" class="img-fluid rounded shadow-sm" alt="Variant Image">
                        @else
                            <p class="text-muted">No image uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
