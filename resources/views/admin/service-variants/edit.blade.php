@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edit Service Variant</h5>
                            <a href="{{ route('service-variants.index') }}" class="btn btn-primary">All Variants</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('service-variants.update', $variant->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Service --}}
                                <div class="mb-4">
                                    <label class="form-label" for="service_id">Select Service</label>
                                    <select name="service_id" id="service_id" class="form-select">
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ $variant->service_id == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Name --}}
                                <div class="mb-4">
                                    <label class="form-label" for="name">Variant Name</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $variant->name) }}" class="form-control">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Slug --}}
                                <div class="mb-4">
                                    <label class="form-label" for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug"
                                        value="{{ old('slug', $variant->slug) }}" class="form-control">
                                    @error('slug')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="mb-4">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $variant->description) }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Price --}}
                                <div class="mb-4">
                                    <label class="form-label" for="price">Price</label>
                                    <input type="number" step="0.01" name="price" id="price"
                                        value="{{ old('price', $variant->price) }}" class="form-control">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Duration --}}
                                <div class="mb-4">
                                    <label class="form-label" for="duration">Duration</label>
                                    <input type="text" name="duration" id="duration"
                                        value="{{ old('duration', $variant->duration) }}" class="form-control">
                                    @error('duration')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Image --}}
                                <div class="mb-4">
                                    <label class="form-label" for="image">Variant Image</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    @if ($variant->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $variant->image) }}" width="120"
                                                alt="Variant Image">
                                        </div>
                                    @endif
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-4">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" {{ $variant->status === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $variant->status === 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Submit --}}
                                <button type="submit" class="btn btn-success">Update Variant</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
