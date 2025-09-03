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

                            <form action="{{ route('admin.deals.update', $deal->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label class="form-label">Deal Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $deal->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Slug --}}
                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        value="{{ old('slug', $deal->slug) }}">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $deal->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image --}}
                                <div class="mb-3">
                                    <label class="form-label">Deal Image</label>
                                    <input type="file" name="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if ($deal->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $deal->image) }}" alt="Deal Image"
                                                class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @endif
                                </div>

                                {{-- Price & Services Total --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Final Price</label>
                                        <input type="number" step="0.01" name="price"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price', $deal->price) }}">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Services Price</label>
                                        <input type="number" step="0.01" name="services_total"
                                            class="form-control @error('services_total') is-invalid @enderror"
                                            value="{{ old('services_total', $deal->services_total) }}">
                                        @error('services_total')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Date Range --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="start_date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            value="{{ old('start_date', $deal->start_date) }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="end_date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            value="{{ old('end_date', $deal->end_date) }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active"
                                            {{ old('status', $deal->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $deal->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Services --}}
                                <div class="mb-3">
                                    <label class="form-label">Select Services</label>
                                    <select name="services[]" class="form-select @error('services') is-invalid @enderror"
                                        multiple>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ in_array($service->id, old('services', $deal->services->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $service->name }} ({{ $service->price }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('services')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-warning">Update Deal</button>
                                    <a href="{{ route('admin.deals.index') }}" class="btn btn-secondary">Cancel</a>
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
