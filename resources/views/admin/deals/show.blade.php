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
                            <h5 class="mb-0">View Deal</h5>
                            <div>

                                {!! render_delete_button($deal->id, route('deals.destroy', $deal->id), false) !!}
                                {!! render_edit_button(route('deals.edit', $deal->slug), false) !!}
                                {!! render_index_button(route('deals.index'), 'All Deals', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">

                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Deal Name:</label>
                                        <div>{{ $deal->name ?? '-' }}</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description:</label>
                                        <div>{{ $deal->description ?? '-' }}</div>
                                    </div>

                                    <!-- Price & Services Total -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Price:</label>
                                            <div class="text-primary fw-semibold">
                                                Rs {{ number_format($deal->price, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Services Total:</label>
                                            <div>
                                                Rs {{ number_format($deal->services_total ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dates -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Start Date:</label>
                                            <div>
                                                {{ $deal->start_date ? \Carbon\Carbon::parse($deal->start_date)->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">End Date:</label>
                                            <div>
                                                {{ $deal->end_date ? \Carbon\Carbon::parse($deal->end_date)->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Status:</label>
                                        <div>{!! $deal->status_badge !!}</div>
                                    </div>

                                    <!-- Services -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Services:</label>
                                        <div>
                                            @forelse($deal->serviceVariants as $service)
                                                <span class="badge bg-info text-dark me-1 mb-1">
                                                    {{ $service->name }} (Rs {{ number_format($service->price, 2) }})
                                                </span>
                                            @empty
                                                <span>—</span>
                                            @endforelse
                                        </div>
                                    </div>


                                </div>

                                <!-- Right Column (Image Preview) -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold">Media Preview:</label><br>

                                    @if ($deal->image && file_exists(public_path('storage/' . $deal->image)))
                                        <img src="{{ asset('storage/' . $deal->image) }}" alt="{{ $deal->name }}"
                                            class="img-fluid rounded shadow-sm mb-2 js-media-preview"
                                            style="max-height:200px; object-fit:cover;"
                                            data-url="{{ asset('storage/' . $deal->image) }}" data-type="image">
                                    @else
                                        <div class="text-muted">No image uploaded</div>
                                    @endif
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
