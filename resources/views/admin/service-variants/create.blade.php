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
                            <h5 class="mb-0">Create Variant</h5>
                            <a href="{{ route('service-variants.index') }}" class="btn btn-primary">All Variants</a>
                        </div>

                        <div class="card-body">

                            <form action="{{ route('service-variants.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <!-- Service Selection -->
                                <div class="mb-4">
                                    <label for="service_id" class="form-label">Select Service</label>
                                    <select name="service_id" id="service_id" class="form-select">
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Image -->
                                <div class="mb-4">
                                    <label for="image" class="form-label">Variant Image</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="form-label">Variant Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                

                                <!-- Description -->
                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Price -->
                                <div class="mb-4">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" name="price" id="price" class="form-control" step="0.01"
                                        value="{{ old('price') }}">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div class="mb-4">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="text" name="duration" id="duration" class="form-control"
                                        value="{{ old('duration') }}" placeholder="e.g., 30 minutes">
                                    @error('duration')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Create Variant</button>
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
