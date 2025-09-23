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
                            <h5 class="mb-0">View Testimonial</h5>
                            <div>

                                {!! render_delete_button($testimonial->id, route('testimonials.destroy', $testimonial->id), false) !!}
                                {!! render_edit_button(route('testimonials.edit', $testimonial->id), false) !!}
                                {!! render_index_button(route('testimonials.index'), 'All Testimonials', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">

                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Name -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Name:</label>
                                        <div>{{ $testimonial->name ?? '-' }}</div>
                                    </div>

                                    <!-- Designation -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Designation:</label>
                                        <div>{{ $testimonial->designation ?? '-' }}</div>
                                    </div>

                                    <!-- Testimonial Text -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Testimonial:</label>
                                        <div>{{ $testimonial->testimonial ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-4">

                                        <!-- Status -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Status:</label>
                                            <div>{!! $testimonial->status_badge !!}</div>
                                        </div>

                                        <!-- Created At -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Submitted On:</label>
                                            <div>
                                                {{ $testimonial->created_at ? $testimonial->created_at->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>

                                        <!-- Updated At -->
                                         <div class="col-md-4">
                                            <label class="form-label fw-bold">Last Updated:</label>
                                            <div>
                                                {{ $testimonial->updated_at ? $testimonial->updated_at->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>
                                    </div>




                                </div>

                                <!-- Right Column (Image Preview) -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold">Image Preview:</label><br>

                                    @if ($testimonial->image && file_exists(public_path('storage/' . $testimonial->image)))
                                        <img src="{{ asset('storage/' . $testimonial->image) }}"
                                            alt="{{ $testimonial->name }}"
                                            class="img-fluid rounded shadow-sm mb-2 js-media-preview"
                                            style="max-height:200px; object-fit:cover;"
                                            data-url="{{ asset('storage/' . $testimonial->image) }}" data-type="image">
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
