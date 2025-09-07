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
                            <h5 class="mb-0">View Gallery</h5>
                            <div>

                                {!! render_delete_button($gallery->id, route('galleries.destroy', $gallery->id), false) !!}
                                {!! render_edit_button(route('galleries.edit', $gallery->slug), false) !!}
                                {!! render_index_button(route('galleries.index'), 'All Galleries', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Deal Card -->
                                    <div class="card shadow-lg border-0 rounded-4">
                                        @if ($deal->image)
                                            <img src="{{ asset('storage/' . $deal->image) }}"
                                                class="card-img-top rounded-top-4" alt="{{ $deal->name }}">
                                        @endif

                                        <div class="card-body p-4">
                                            <!-- Deal Title -->
                                            <h2 class="card-title fw-bold mb-3">{{ $deal->name }}</h2>

                                            <!-- Status Badge -->
                                            <span
                                                class="badge bg-{{ $deal->status === 'active' ? 'success' : 'secondary' }} mb-3 px-3 py-2">
                                                {{ ucfirst($deal->status) }}
                                            </span>

                                            <!-- Deal Info -->
                                            <p class="card-text text-muted">
                                                {{ $deal->description ?? 'No description available.' }}</p>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="fw-semibold">Price:</h6>
                                                    <p class="text-primary fw-bold">${{ number_format($deal->price, 2) }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-semibold">Services Total:</h6>
                                                    <p>${{ number_format($deal->services_total ?? 0, 2) }}</p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="fw-semibold">Start Date:</h6>
                                                    <p>{{ $deal->start_date ? \Carbon\Carbon::parse($deal->start_date)->format('M d, Y') : '—' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-semibold">End Date:</h6>
                                                    <p>{{ $deal->end_date ? \Carbon\Carbon::parse($deal->end_date)->format('M d, Y') : '—' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Created/Updated -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">Created by:
                                                        {{ optional($deal->creator)->name ?? 'System' }}</small>
                                                </div>
                                                <div class="col-md-6 text-md-end">
                                                    <small class="text-muted">Last updated:
                                                        {{ $deal->updated_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="card-footer bg-light text-center">
                                            <a href="{{ route('deals.index') }}"
                                                class="btn btn-outline-secondary btn-sm rounded-pill px-4">Back to Deals</a>
                                            <a href="{{ route('deals.edit', $deal->id) }}"
                                                class="btn btn-primary btn-sm rounded-pill px-4">Edit Deal</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Media Preview Modal -->
            @include('admin.pages-partials.preview_modal')

        </div>
    @endsection
