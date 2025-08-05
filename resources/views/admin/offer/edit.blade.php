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
                            <h5 class="mb-0">Edit Offer</h5>
                            <div>
                                {!! render_delete_button($offer->id, route('offers.destroy', $offer->id), false) !!}
                                {!! render_view_button(route('offers.show', $offer->slug), false) !!}
                                {!! render_index_button(route('offers.index'), 'All Offers', false) !!}
                            </div>
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


                            <form action="{{ route('offers.update', $offer->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <!-- Name -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="name" class="form-label">Offer Name</label>
                                        <input type="text" name="name" value="{{ old('name', $offer->name) }}"
                                            class="form-control" required>
                                    </div>

                                    <!-- Offer Code -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="offer_code" class="form-label">Offer Code</label>
                                        <input type="text" name="offer_code"
                                            value="{{ old('offer_code', $offer->offer_code) }}" class="form-control">
                                    </div>

                                    <!-- Image -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="image" class="form-label">Offer Image</label>
                                        @if ($offer->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $offer->image) }}" alt="Current Image"
                                                    width="150">
                                            </div>
                                        @endif
                                        <input type="file" name="image" class="form-control">
                                    </div>


                                    <!-- Description -->
                                    <div class="mb-3 col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description', $offer->description) }}</textarea>
                                    </div>

                                    <!-- Type -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select" required>
                                            <option value="percentage"
                                                {{ $offer->type === 'percentage' ? 'selected' : '' }}>
                                                Percentage</option>
                                            <option value="flat" {{ $offer->type === 'flat' ? 'selected' : '' }}>Flat
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Value -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="value" class="form-label">Value</label>
                                        <input type="number" step="0.01" name="value"
                                            value="{{ old('value', $offer->value) }}" class="form-control" required>
                                    </div>

                                    <!-- Usage Limits -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="max_total_uses" class="form-label">Max Total Uses</label>
                                        <input type="number" name="max_total_uses"
                                            value="{{ old('max_total_uses', $offer->max_total_uses) }}"
                                            class="form-control">
                                    </div>

                                    <!-- Dates -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="starts_at" class="form-label">Start Date</label>
                                        <input type="datetime-local" name="starts_at"
                                            value="{{ old('starts_at', optional($offer->starts_at)->format('Y-m-d\TH:i')) }}"
                                            class="form-control">
                                    </div>

                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="ends_at" class="form-label">End Date</label>
                                        <input type="datetime-local" name="ends_at"
                                            value="{{ old('ends_at', optional($offer->ends_at)->format('Y-m-d\TH:i')) }}"
                                            class="form-control">
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="active" {{ $offer->status === 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="inactive" {{ $offer->status === 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>



                                    {{-- <div class="mb-3 col-md-4 col-12">
                                        <label for="max_uses_per_user" class="form-label">Max Uses per User</label>
                                        <input type="number" name="max_uses_per_user"
                                            value="{{ old('max_uses_per_user', $offer->max_uses_per_user) }}"
                                            class="form-control">
                                    </div> --}}


                                    {{-- <!-- Lifecycle -->
                                    <div class="mb-3 col-md-4 col-12">
                                        <label for="lifecycle" class="form-label">Lifecycle</label>
                                        <select name="lifecycle" class="form-select">
                                            <option value="active" {{ $offer->lifecycle === 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="expired"
                                                {{ $offer->lifecycle === 'expired' ? 'selected' : '' }}>
                                                Expired</option>
                                            <option value="upcoming"
                                                {{ $offer->lifecycle === 'upcoming' ? 'selected' : '' }}>
                                                Upcoming</option>
                                            <option value="disabled"
                                                {{ $offer->lifecycle === 'disabled' ? 'selected' : '' }}>
                                                Disabled</option>
                                        </select>
                                    </div> --}}

                                </div>

                                <button type="submit" class="btn btn-primary">Update Offer</button>
                                <a href="{{ route('offers.index') }}" class="btn btn-secondary">Cancel</a>
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
