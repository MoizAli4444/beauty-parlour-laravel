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
                            <h5 class="mb-0">View Blog</h5>
                            <div>

                                {!! render_delete_button($blog->id, route('blogs.destroy', $blog->id), false) !!}
                                {!! render_edit_button(route('blogs.edit', $blog->id), false) !!}
                                {!! render_index_button(route('blogs.index'), 'All blogs', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">

                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Title:</label>
                                        <div>{{ $blog->title ?? '-' }}</div>
                                    </div>

                                    <!-- Slug -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Slug:</label>
                                        <div>{{ $blog->slug ?? '-' }}</div>
                                    </div>

                                    <!-- Excerpt -->
                                    @if ($blog->excerpt)
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Excerpt:</label>
                                            <div class="text-muted fst-italic">{{ $blog->excerpt }}</div>
                                        </div>
                                    @endif

                                    <!-- Content -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Content:</label>
                                        <div>{!!$blog->content !!}</div>
                                    </div>

                                    <div class="row mb-4">
                                        <!-- Status -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Status:</label>
                                            <div>{!! $blog->status_badge !!}</div>
                                        </div>

                                        <!-- Published At -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Published On:</label>
                                            <div>
                                                {{ $blog->published_at ? $blog->published_at->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>

                                        <!-- Views -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Views:</label>
                                            <div>{{ $blog->views }}</div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <!-- Author -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Author:</label>
                                            <div>{{ $blog->author->name ?? '—' }}</div>
                                        </div>

                                        <!-- Service -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Service:</label>
                                            <div>{{ $blog->service->name ?? '—' }}</div>
                                        </div>
                                    </div>

                                    <!-- Timestamps -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Created At:</label>
                                            <div>{{ $blog->created_at?->format('d M Y h:i A') ?? '—' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Last Updated:</label>
                                            <div>{{ $blog->updated_at?->format('d M Y h:i A') ?? '—' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column (Image Preview) -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold">Image Preview:</label><br>

                                    {!! getImage($blog->image, true, ) !!}
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
