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
                            <h5 class="mb-0">View Booking Review</h5>
                            <div>

                                {!! render_index_button(route('booking-reviews.index'), 'All Reviews', false) !!}
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="row justify-content-center">

                                <!-- Booking & Customer Info -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Booking</label>
                                    <div>{{ $review->booking->id ?? 'N/A' }}</div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Customer</label>
                                    <div>{{ $review->customer->name ?? 'N/A' }}</div>
                                </div>

                                <!-- Rating & Status -->    
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Rating</label>
                                    <div>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                        @endfor
                                        <span>({{ $review->rating }}/5)</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Status</label>
                                    <div>
                                        {!! $review->status_badge !!}
                                    </div>

                                </div>

                                <!-- Moderator -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Moderated By</label>
                                    <div>{{ $review->moderator->name ?? 'Not Moderated Yet' }}</div>
                                </div>

                                <!-- Created Date -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Submitted At</label>
                                    <p>{{ $review->created_at->format('d M, Y h:i A') }}</p>
                                </div>
                            </div>

                            <!-- Review Text -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Review</label>
                                <div class="p-3 border rounded bg-light">
                                    {{ $review->review }}
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
