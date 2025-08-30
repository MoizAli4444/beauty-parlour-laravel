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
                            <h5 class="mb-0">View Offer</h5>
                            <div>

                                {!! render_delete_button($offer->id, route('offers.destroy', $offer->id), false) !!}
                                {!! render_edit_button(route('offers.edit', $offer->slug), false) !!}
                                {!! render_index_button(route('offers.index'), 'All Offers', false) !!}
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-8">

                                        <!-- Offer Name -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Offer Name:</label>
                                            <div>{{ $offer->name }}</div>
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Description:</label>
                                            <div>{{ $offer->description ?? '-' }}</div>
                                        </div>

                                        <!-- Offer Code -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Offer Code:</label>
                                            <div>{{ $offer->offer_code ?? '-' }}</div>
                                        </div>

                                        <div class="row">



                                            <div class="col-md-4 mb-4">
                                                <!-- Type -->
                                                <label class="form-label fw-bold">Type:</label>
                                                <div class="text-capitalize">{{ $offer->type }}</div>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <!-- Value -->
                                                <label class="form-label fw-bold">Value:</label>
                                                <div>
                                                    @if ($offer->type === 'percentage')
                                                        {{ $offer->value }}%
                                                    @else
                                                        Rs {{ number_format($offer->value, 2) }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <!-- Max Total Uses -->
                                                <label class="form-label fw-bold">Max Total Uses:</label>
                                                <div>{{ $offer->max_total_uses ?? '-' }}</div>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <!-- Start Date -->
                                                <label class="form-label fw-bold">Start Date:</label>
                                                <div>
                                                    {{ $offer->starts_at ? $offer->starts_at->format('d M Y, h:i A') : '-' }}
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <!-- End Date -->
                                                <label class="form-label fw-bold">End Date:</label>
                                                <div>
                                                    {{ $offer->ends_at ? $offer->ends_at->format('d M Y, h:i A') : '-' }}
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <!-- Status -->
                                                
                                                <label class="form-label fw-bold">Status:</label>
                                                <div>{!! $offer->status_badge !!}</div>
                                            </div>

                                        </div>








                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-4 text-center">
                                        <label class="form-label fw-bold">Offer Image:</label><br>

                                        @if (!empty($offer->image) && file_exists(public_path('storage/' . $offer->image)))
                                            <img src="{{ asset('storage/' . $offer->image) }}" alt="Offer Image"
                                                data-url="{{ asset('storage/' . $offer->image) }}" data-type="image"
                                                class="img-fluid rounded shadow-sm js-media-preview"
                                                style="max-height:200px; object-fit:cover;">
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
        <!-- / Content -->

        <!-- Media Preview Modal -->
        @include('admin.pages-partials.preview_modal')

    </div>
@endsection
