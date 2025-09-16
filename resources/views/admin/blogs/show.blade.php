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

                        {{-- <div class="card-body">
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

                        </div> --}}


                        <div class="card shadow-lg border-0 rounded-3">
                            @if ($blog->image && Storage::disk('public')->exists($blog->image))
                                <img src="{{ asset('storage/' . $blog->image) }}" class="card-img-top rounded-top"
                                    alt="{{ $blog->title }}" style="max-height: 400px; object-fit: cover;">
                            @endif

                            <div class="card-body p-4">
                                {{-- Title + Status --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h2 class="card-title mb-0">{{ $blog->title }}</h2>
                                    {!! $blog->status_badge !!}
                                </div>

                                {{-- Excerpt --}}
                                @if ($blog->excerpt)
                                    <p class="text-muted fst-italic">{{ $blog->excerpt }}</p>
                                @endif

                                {{-- Meta --}}
                                <div class="mb-3 small text-muted">
                                    <i class="bi bi-person-circle me-1"></i>
                                    {{ $blog->author->name ?? 'Unknown Author' }}

                                    @if ($blog->service)
                                        | <i class="bi bi-scissors me-1"></i> {{ $blog->service->name }}
                                    @endif

                                    | <i class="bi bi-calendar-event me-1"></i>
                                    {{ $blog->published_at ? $blog->published_at->format('d M, Y') : 'Not Published' }}

                                    | <i class="bi bi-eye me-1"></i> {{ $blog->views }} views
                                </div>

                                {{-- Content --}}
                                <div class="card-text">
                                    {!! nl2br(e($blog->content)) !!}
                                </div>
                            </div>

                            <div class="card-footer bg-white text-end">
                                <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
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
