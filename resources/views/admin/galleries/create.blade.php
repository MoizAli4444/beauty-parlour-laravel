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


                                {{-- Title --}}
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">Title</label>
                                    <input type="text" name="title" id="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Enter gallery title" value="{{ old('title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                        rows="4" placeholder="Write something about this item...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Media Type --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Media Type</label>
                                    <select name="media_type" class="form-select @error('media_type') is-invalid @enderror">
                                        <option value="image" {{ old('media_type') == 'image' ? 'selected' : '' }}>Image
                                        </option>
                                        <option value="video" {{ old('media_type') == 'video' ? 'selected' : '' }}>Video
                                        </option>
                                    </select>
                                    @error('media_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- File Upload --}}
                                <div class="mb-3">
                                    <label for="file_path" class="form-label fw-bold">Upload File</label>
                                    <input type="file" name="file_path" id="file_path"
                                        class="form-control @error('file_path') is-invalid @enderror"
                                        accept="image/*,video/*" onchange="previewFile(event)">
                                    @error('file_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Preview --}}
                                    <div id="preview" class="mt-3 text-center"></div>
                                </div>

                                {{-- Featured --}}
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="featured" id="featured"
                                        {{ old('featured') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="featured">Mark as Featured</label>
                                </div>

                                {{-- Alt Text --}}
                                <div class="mb-3">
                                    <label for="alt_text" class="form-label fw-bold">Alt Text (SEO)</label>
                                    <input type="text" name="alt_text" id="alt_text"
                                        class="form-control @error('alt_text') is-invalid @enderror"
                                        placeholder="Alternative text for accessibility" value="{{ old('alt_text') }}">
                                    @error('alt_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('galleries.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Save Gallery Item
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
