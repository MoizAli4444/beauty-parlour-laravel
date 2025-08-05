@extends('admin.layouts.app')

@push('scripts')
    @include('admin.staff.js.css')
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
                            <h5 class="mb-0">Create Offer</h5>
                            <a href="{{ route('offers.index') }}" class="btn btn-warning">All Offers</a>
                        </div>

                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <form action="{{ route('offers.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">

                                    <!-- Name -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="name" class="form-label">Offer Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name') }}" placeholder="Enter offer name">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Offer Code -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="offer_code" class="form-label">Offer Code</label>
                                        <input type="text" name="offer_code" id="offer_code" class="form-control"
                                            value="{{ old('offer_code') }}">
                                        @error('offer_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <!-- Image Upload -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="image" class="form-label">Offer Image</label>
                                        <input type="file" name="image" id="image" class="form-control">
                                        @error('image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <!-- Description -->
                                    <div class="mb-4 col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="4"
                                            placeholder="Write a short description...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Type -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-select">
                                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                                Percentage</option>
                                            <option value="flat" {{ old('type') == 'flat' ? 'selected' : '' }}>Flat
                                            </option>
                                        </select>
                                        @error('type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Value -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="value" class="form-label">Value</label>
                                        <input type="number" step="0.01" name="value" id="value"
                                            class="form-control" value="{{ old('value') }}">
                                        @error('value')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Max Total Uses -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="max_total_uses" class="form-label">Max Total Uses</label>
                                        <input type="number" name="max_total_uses" id="max_total_uses" class="form-control"
                                            value="{{ old('max_total_uses') }}">
                                        @error('max_total_uses')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Starts At -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="starts_at" class="form-label">Start Date</label>
                                        <input type="datetime-local" name="starts_at" id="starts_at" class="form-control"
                                            value="{{ old('starts_at') }}">
                                        @error('starts_at')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Ends At -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="ends_at" class="form-label">End Date</label>
                                        <input type="datetime-local" name="ends_at" id="ends_at" class="form-control"
                                            value="{{ old('ends_at') }}">
                                        @error('ends_at')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>



                                    {{-- <!-- Max Uses Per User -->
                                <div class="mb-4 col-md-4 col-12">
                                    <label for="max_uses_per_user" class="form-label">Max Uses Per User</label>
                                    <input type="number" name="max_uses_per_user" id="max_uses_per_user"
                                        class="form-control" value="{{ old('max_uses_per_user') }}">
                                    @error('max_uses_per_user')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div> --}}





                                    <!-- Status -->
                                    <div class="mb-4 col-md-4 col-12">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- <!-- Lifecycle -->
                                <div class="mb-4 col-md-4 col-12">
                                    <label for="lifecycle" class="form-label">Lifecycle</label>
                                    <select name="lifecycle" id="lifecycle" class="form-select">
                                        <option value="active" {{ old('lifecycle') === 'active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="expired" {{ old('lifecycle') === 'expired' ? 'selected' : '' }}>
                                            Expired</option>
                                        <option value="upcoming" {{ old('lifecycle') === 'upcoming' ? 'selected' : '' }}>
                                            Upcoming</option>
                                        <option value="disabled" {{ old('lifecycle') === 'disabled' ? 'selected' : '' }}>
                                            Disabled</option>
                                    </select>
                                    @error('lifecycle')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div> --}}

                                </div>

                                <!-- Submit -->
                                <button type="submit" class="btn btn-warning">Create Offer</button>
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

@push('scripts')
    @include('admin.staff.js.script')
@endpush
