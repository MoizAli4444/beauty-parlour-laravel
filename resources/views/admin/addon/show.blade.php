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
                            <h5 class="mb-0">View Service</h5>
                            <div>

                                {!! render_delete_button($service->id, route('services.destroy', $service->id), false) !!}
                                {!! render_edit_button(route('services.edit', $service->slug), false) !!}
                                {!! render_index_button(route('services.index'), 'All Services', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-8">
                                    <!-- Addon Name -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Addon Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $addon->name) }}" required>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description', $addon->description) }}</textarea>
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Price (PKR)</label>
                                        <input type="number" step="0.01" name="price" class="form-control"
                                            value="{{ old('price', $addon->price) }}" required>
                                    </div>

                                    <!-- Duration -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Duration (minutes)</label>
                                        <input type="number" name="duration" class="form-control"
                                            value="{{ old('duration', $addon->duration) }}" required>
                                    </div>

                                    <!-- Sort Order -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Sort Order</label>
                                        <input type="number" name="sort_order" class="form-control"
                                            value="{{ old('sort_order', $addon->sort_order) }}">
                                    </div>

                                    <!-- Gender -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="0" {{ $addon->gender == 0 ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="1" {{ $addon->gender == 1 ? 'selected' : '' }}>Male</option>
                                            <option value="2" {{ $addon->gender == 2 ? 'selected' : '' }}>Both</option>
                                        </select>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="1" {{ $addon->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $addon->status == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Addon Image</label><br>

                                    @if (!empty($addon->image) && file_exists(public_path('storage/' . $addon->image)))
                                        <img src="{{ asset('storage/' . $addon->image) }}" alt="Addon Image"
                                            class="rounded shadow-sm mb-2" style="width: 250px; object-fit: cover;">
                                    @else
                                        <div class="text-muted mb-2">No image uploaded</div>
                                    @endif

                                    <input type="file" name="image" class="form-control">
                                    <small class="text-muted">Max size: 5MB</small>
                                </div>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>
@endsection
