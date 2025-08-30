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
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Title:</label>
                                        <div>{{ $gallery->title ?? '-' }}</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description:</label>
                                        <div>{{ $gallery->description ?? '-' }}</div>
                                    </div>

                                    <!-- Featured & File size -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">

                                            <label class="form-label fw-bold">Featured:</label>
                                            <div>{!! $gallery->featured_badge !!}</div>


                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">File Size:</label>
                                            <div>
                                                @if ($gallery->file_size)
                                                    @php
                                                        $sizeKB = $gallery->file_size / 1024;
                                                        $sizeDisplay =
                                                            $sizeKB < 1024
                                                                ? number_format($sizeKB, 2) . ' KB'
                                                                : number_format($sizeKB / 1024, 2) . ' MB';
                                                    @endphp
                                                    {{ $sizeDisplay }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Size & Status -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">File Size:</label>
                                            <div>
                                                @if ($gallery->file_size)
                                                    @php
                                                        $sizeKB = $gallery->file_size / 1024;
                                                        $sizeDisplay =
                                                            $sizeKB < 1024
                                                                ? number_format($sizeKB, 2) . ' KB'
                                                                : number_format($sizeKB / 1024, 2) . ' MB';
                                                    @endphp
                                                    {{ $sizeDisplay }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Status:</label>
                                            <div>{!! $gallery->status_badge !!}</div>
                                        </div>
                                    </div>

                                    <!-- Created & Updated At -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Created At:</label>
                                            <div>{{ $gallery->created_at->format('d M Y, h:i A') }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Updated At:</label>
                                            <div>{{ $gallery->updated_at->format('d M Y, h:i A') }}</div>
                                        </div>
                                    </div>

                                </div>



                                <!-- Right Column (Media Preview) -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold">Media Preview:</label><br>

                                    @if ($gallery->media_type === 'image' && file_exists(public_path('storage/' . $gallery->file_path)))
                                        <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                            alt="{{ $gallery->alt_text }}"
                                            class="img-fluid rounded shadow-sm mb-2 js-media-preview"
                                            style="max-height:200px; object-fit:cover;"
                                            data-url="{{ asset('storage/' . $gallery->file_path) }}" data-type="image">
                                    @elseif($gallery->media_type === 'video' && file_exists(public_path('storage/' . $gallery->file_path)))
                                        <video controls class="img-fluid rounded shadow-sm mb-2 js-media-preview"
                                            style="max-height:200px; object-fit:cover;"
                                            data-url="{{ asset('storage/' . $gallery->file_path) }}" data-type="video">
                                            <source src="{{ asset('storage/' . $gallery->file_path) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <div class="text-muted">No media uploaded</div>
                                    @endif
                                </div>

                            </div>
                        </div>




                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Media Preview Modal -->
            @include('admin.galleries.partials.preview_modal')

        </div>
    @endsection
