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
                                                ${{ number_format($deal->price, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Services Total:</label>
                                            <div>
                                                ${{ number_format($deal->services_total ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dates -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Start Date:</label>
                                            <div>
                                                {{ $deal->start_date ? \Carbon\Carbon::parse($deal->start_date)->format('d M Y') : '—' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">End Date:</label>
                                            <div>
                                                {{ $deal->end_date ? \Carbon\Carbon::parse($deal->end_date)->format('d M Y') : '—' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Status:</label>
                                        <div>
                                            <span
                                                class="badge bg-{{ $deal->status === 'active' ? 'success' : 'secondary' }} px-3 py-2">
                                                {{ ucfirst($deal->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Created & Updated At -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Created At:</label>
                                            <div>{{ $deal->created_at->format('d M Y, h:i A') }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Updated At:</label>
                                            <div>{{ $deal->updated_at->format('d M Y, h:i A') }}</div>
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


                                <!-- Back & Edit Buttons -->
                                <div class="mt-4 text-center">
                                    <a href="{{ route('deals.index') }}"
                                        class="btn btn-outline-secondary rounded-pill px-4">Back to Deals</a>
                                    <a href="{{ route('deals.edit', $deal->id) }}"
                                        class="btn btn-primary rounded-pill px-4">Edit Deal</a>
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
