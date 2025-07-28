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

                            <form action="{{ route('addons.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Name -->
                                <div class="mb-4">
                                    <label class="form-label" for="name">Addon Name</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        value="{{ old('name', $addon->name ?? '') }}" placeholder="Enter addon name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4"
                                        placeholder="Write a short description...">{{ old('description', $addon->description ?? '') }}</textarea>
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

                                </div>

                                <!-- Price -->
                                <div class="mb-4">
                                    <label class="form-label" for="price">Price</label>
                                    <input type="number" name="price" step="0.01" class="form-control" id="price"
                                        value="{{ old('price', $addon->price ?? '') }}" placeholder="Enter price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Duration Minutes -->
                                <div class="mb-4">
                                    <label class="form-label" for="duration_minutes">Duration (in minutes)</label>
                                    <input type="number" name="duration_minutes" class="form-control" id="duration_minutes"
                                        value="{{ old('duration_minutes', $addon->duration_minutes ?? '') }}"
                                        placeholder="e.g. 30">
                                    @error('duration_minutes')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="mb-4">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="0"
                                            {{ old('gender', $addon->gender ?? '') == 0 ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="1"
                                            {{ old('gender', $addon->gender ?? '') == 1 ? 'selected' : '' }}>Male</option>
                                        <option value="2"
                                            {{ old('gender', $addon->gender ?? '') == 2 ? 'selected' : '' }}>Both</option>
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
                                            {{ old('is_active', $addon->is_active ?? '') == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0"
                                            {{ old('is_active', $addon->is_active ?? '') == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('is_active')
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
