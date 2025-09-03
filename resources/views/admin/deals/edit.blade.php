@extends('admin.layouts.app')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Basic Layout -->
            <div class="row mb-6 gy-6">
                <div class="col-xl">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edit Gallery</h5>

                            <div>
                                {!! render_delete_button($gallery->id, route('galleries.destroy', $gallery->id), false) !!}
                                {!! render_view_button(route('galleries.show', $gallery->slug), false) !!}
                                {!! render_index_button(route('galleries.index'), 'All Galleries', false) !!}

                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('galleries.update', $gallery->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-semibold">Title</label>
                                    <input type="text" name="title" id="title"
                                        class="form-control shadow-sm @error('title') is-invalid @enderror"
                                        value="{{ old('title', $gallery->title) }}" placeholder="Enter gallery title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold">Description</label>
                                    <textarea name="description" id="description" class="form-control shadow-sm @error('description') is-invalid @enderror"
                                        rows="3" placeholder="Write a short description...">{{ old('description', $gallery->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Service (if gallery belongs to service) -->
                                <div class="mb-4">
                                    <label class="form-label" for="service_id">Service</label>
                                    <select name="service_id" id="service_id" class="form-select">
                                        <option value="">-- Select Service --</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ old('service_id', $gallery->service_id) == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <!-- File Upload & Preview -->
                                <div class="mb-3">
                                    <label for="file_path" class="form-label fw-semibold">Replace Media (optional)</label>
                                    <input type="file" name="file_path" id="file_path"
                                        class="form-control shadow-sm @error('file_path') is-invalid @enderror">
                                    @error('file_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Media Preview -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Current Media</label>
                                    <div class="border rounded p-3 bg-light text-center shadow-sm">
                                        @if ($gallery->media_type === 'image')
                                            <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                                alt="{{ $gallery->alt_text }}" class="img-fluid rounded"
                                                style="max-height:200px;">
                                        @elseif($gallery->media_type === 'video')
                                            <video width="320" height="200" controls class="rounded">
                                                <source src="{{ asset('storage/' . $gallery->file_path) }}"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <span class="text-muted">No media available</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Alt Text -->
                                <div class="mb-4">
                                    <label class="form-label" for="alt_text">Alt Text</label>
                                    <input type="text" name="alt_text" class="form-control" id="alt_text"
                                        value="{{ old('alt_text', $gallery->alt_text) }}"
                                        placeholder="For SEO & accessibility">
                                    @error('alt_text')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <!-- Featured -->
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                        value="1" {{ old('featured', $gallery->featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured">Mark as Featured ⭐</label>
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="status" class="form-label fw-semibold">Status</label>
                                    <select name="status" id="status"
                                        class="form-select shadow-sm @error('status') is-invalid @enderror">
                                        <option value="active"
                                            {{ old('status', $gallery->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $gallery->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success px-4">
                                        Update Gallery
                                    </button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->

    </div>
    <!-- Content wrapper -->
@endsection
