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
                            <h5 class="mb-0">View Booking</h5>
                            <div>

                                {{-- {!! render_delete_button($booking->id, route('bookings.destroy', $booking->id), false) !!} --}}
                                {!! render_edit_button(route('bookings.edit', $review->id), false) !!}
                                {!! render_index_button(route('bookings.index'), 'All Bookings', false) !!}
                            </div>
                        </div>

                        <div class="card-body">




                            <div class="row justify-content-center">
                                <div class="col-md-12">

                                    <!-- Card -->


                                    <!-- Booking & Customer Info -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Booking</label>
                                            <div>{{ $review->booking->id ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Customer</label>
                                            <div>{{ $review->customer->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>

                                    <!-- Rating & Status -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Rating</label>
                                            <div>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                                @endfor
                                                <div>({{ $review->rating }})</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Status</label>
                                            <div
                                                class="badge 
                                                            @if ($review->status === 'approved') bg-success 
                                                            @elseif($review->status === 'rejected') bg-danger 
                                                            @else bg-warning text-dark @endif">
                                                {{ ucfirst($review->status) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Review Text -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Review</label>
                                        <div class="p-3 border rounded bg-light">
                                            {{ $review->review }}
                                        </div>
                                    </div>

                                    <!-- Moderator -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Moderated By</label>
                                        <div>{{ $review->moderator->name ?? 'Not Moderated Yet' }}</div>
                                    </div>

                                    <!-- Created Date -->
                                    <div>
                                        <label class="form-label fw-bold">Submitted At</label>
                                        <p>{{ $review->created_at->format('d M, Y h:i A') }}</p>
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
