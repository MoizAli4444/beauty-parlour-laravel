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
                            <h5 class="mb-0">Create Addon</h5>
                            <a href="{{ route('addons.index') }}" class="btn btn-warning">All Addons</a>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Title -->
                                <div class="mb-4">
                                    <label class="form-label" for="title">Gallery Title</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                        value="{{ old('title', $gallery->title ?? '') }}" placeholder="Enter gallery title">
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4"
                                        placeholder="Write a short description...">{{ old('description', $gallery->description ?? '') }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Service (if gallery belongs to service) -->
                                <div class="mb-4">
                                    <label class="form-label" for="service_id">Service</label>
                                    <select name="service_id" id="service_id" class="form-select">
                                        <option value="">-- Select Service --</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ old('service_id', $gallery->service_id ?? '') == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- File Upload -->
                                <div class="mb-4">
                                    <label class="form-label" for="file">Upload Media</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                    @error('file')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Media Type -->
                                <div class="mb-4">
                                    <label class="form-label" for="media_type">Media Type</label>
                                    <select name="media_type" id="media_type" class="form-select">
                                        <option value="image"
                                            {{ old('media_type', $gallery->media_type ?? '') == 'image' ? 'selected' : '' }}>
                                            Image</option>
                                        <option value="video"
                                            {{ old('media_type', $gallery->media_type ?? '') == 'video' ? 'selected' : '' }}>
                                            Video</option>
                                    </select>
                                    @error('media_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Alt Text -->
                                <div class="mb-4">
                                    <label class="form-label" for="alt_text">Alt Text</label>
                                    <input type="text" name="alt_text" class="form-control" id="alt_text"
                                        value="{{ old('alt_text', $gallery->alt_text ?? '') }}"
                                        placeholder="For SEO & accessibility">
                                    @error('alt_text')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Featured -->
                                <div class="mb-4 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                        {{ old('featured', $gallery->featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured">Featured</label>
                                    @error('featured')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active"
                                            {{ old('status', $gallery->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="inactive"
                                            {{ old('status', $gallery->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <button type="submit" class="btn btn-warning">
                                    Create Addon
                                </button>
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
