@extends('admin.layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

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
                            <h5 class="mb-0">Edit Blog</h5>

                            <div>
                                {!! render_delete_button($blog->id, route('blogs.destroy', $blog->id), false) !!}
                                {!! render_view_button(route('blogs.show', $blog->id), false) !!}
                                {!! render_index_button(route('blogs.index'), 'All Blogs', false) !!}

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

                            <form action="{{ route('blogs.update', $blog->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Title --}}
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $blog->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Excerpt --}}
                                <div class="mb-3">
                                    <label class="form-label">Excerpt</label>
                                    <textarea name="excerpt" rows="3" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Content (CKEditor) --}}
                                <div class="mb-3">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea id="editor" name="content" class="form-control @error('content') is-invalid @enderror" rows="8"
                                        required>
                {{ old('content', $blog->content) }}
            </textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image --}}
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                   {!! getImage($blog->image, true) !!}
                                    <input type="file" name="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="draft"
                                            {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published"
                                            {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Published At --}}
                                <div class="mb-3">
                                    <label class="form-label">Published At</label>
                                    <input type="datetime-local" name="published_at"
                                        class="form-control @error('published_at') is-invalid @enderror"
                                        value="{{ old('published_at', optional($blog->published_at)->format('Y-m-d\TH:i')) }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('blogs.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Blog</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->

        <!-- Media Preview Modal -->
        @include('admin.pages-partials.preview_modal')



    </div>
    <!-- Content wrapper -->
@endsection

@push('scripts')
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
