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
                            <h5 class="mb-0">Edit Addon</h5>

                            <div>
                                {!! render_delete_button($addon->id, route('addons.destroy', $addon->id), false) !!}
                                {!! render_view_button(route('addons.show', $addon->slug), false) !!}
                                {!! render_index_button(route('addons.index'), 'All Addons', false) !!}

                            </div>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('addons.update', $addon->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Name -->
                                <div class="mb-4">
                                    <label class="form-label" for="name">Addon Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $addon->name) }}" placeholder="Enter addon name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Slug (Optional - Readonly or Hidden) -->
                                <input type="hidden" name="slug" value="{{ old('slug', $addon->slug) }}">

                                <!-- Description -->
                                <div class="mb-4">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4"
                                        placeholder="Write a short description...">{{ old('description', $addon->description) }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="mb-4">
                                    <label class="form-label" for="image">Addon Image</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    @if ($addon->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $addon->image) }}" alt="Addon Image"
                                                width="120">
                                        </div>
                                    @endif
                                </div>

                                <!-- Price -->
                                <div class="mb-4">
                                    <label class="form-label" for="price">Price (in PKR)</label>
                                    <input type="number" step="0.01" name="price" id="price" class="form-control"
                                        value="{{ old('price', $addon->price) }}" placeholder="Enter price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div class="mb-4">
                                    <label class="form-label" for="duration_minutes">Duration (in minutes)</label>
                                    <input type="number" name="duration_minutes" id="duration_minutes" class="form-control"
                                        value="{{ old('duration_minutes', $addon->duration_minutes) }}"
                                        placeholder="e.g. 30">
                                    @error('duration_minutes')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="mb-4">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="0" {{ old('gender', $addon->gender) == 0 ? 'selected' : '' }}>
                                            Female</option>
                                        <option value="1" {{ old('gender', $addon->gender) == 1 ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="2" {{ old('gender', $addon->gender) == 2 ? 'selected' : '' }}>
                                            Both</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label" for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-select">
                                        <option value="1"
                                            {{ old('is_active', $addon->is_active) == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0"
                                            {{ old('is_active', $addon->is_active) == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('is_active')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <button type="submit" class="btn btn-primary">
                                    Update Addon
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
