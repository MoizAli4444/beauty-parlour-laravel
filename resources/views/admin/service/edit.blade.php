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
                            <h5 class="mb-0">Edit Service</h5>

                            <div>
                              {!! render_delete_button($service->id, route('services.destroy', $service->id), false) !!}
                                {!! render_view_button(route('services.show', $service->slug), false) !!}
                                {!! render_index_button(route('services.index'), 'All Services', false) !!}

                            </div>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('services.update', $service->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Name -->
                                <div class="mb-4">
                                    <label class="form-label" for="name">Service Name</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        value="{{ old('name', $service->name) }}" placeholder="Enter service name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4"
                                        placeholder="Write a short description...">{{ old('description', $service->description) }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="mb-4">
                                    <label class="form-label" for="image">Service Image</label>
                                    <input type="file" name="image" id="image" class="form-control">

                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    @if (!empty($service->image) && file_exists(public_path('storage/' . $service->image)))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $service->image) }}" alt="Service Image"
                                                width="120">
                                        </div>
                                    @endif


                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active"
                                            {{ old('status', $service->status) === 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $service->status) === 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <button type="submit" class="btn btn-warning">
                                    Update Service
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
