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
                            <h5 class="mb-0">Edit Deal</h5>

                            <div>
                                {!! render_delete_button($deal->id, route('deals.destroy', $deal->id), false) !!}
                                {!! render_view_button(route('deals.show', $deal->slug), false) !!}
                                {!! render_index_button(route('deals.index'), 'All Deals', false) !!}

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

                            <form action="{{ route('deals.update', $deal->id) }}" method="POST"
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
                                                class="img-thumbnail img-fluid rounded shadow-sm mb-2 js-media-preview"
                                                style="max-width: 150px;" data-url="{{ asset('storage/' . $deal->image) }}"
                                                data-type="image">
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
                                        <input type="datetime-local" name="start_date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            value="{{ old('start_date', optional($deal->start_date)->format('Y-m-d\TH:i')) }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="datetime-local" name="end_date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            value="{{ old('end_date', optional($deal->end_date)->format('Y-m-d\TH:i')) }}">
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
                                {{-- <div class="mb-3">
                                    <label class="form-label">Select Services</label>
                                    <select name="services[]" class="form-select @error('services') is-invalid @enderror"
                                        multiple>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ in_array($service->id, old('services', $deal->serviceVariants ? $deal->serviceVariants->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                {{ $service->name }} ({{ $service->price }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('services')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                {{-- Services --}}
                                {{-- Services --}}
                                {{-- Services --}}
                                <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css"
                                    rel="stylesheet">
                                <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

                                <select id="services" name="service_variant_ids[]" multiple>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            {{ in_array($service->id, old('services', $deal->serviceVariants ? $deal->serviceVariants->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                            {{ $service->name }} ({{ $service->price }})
                                        </option>
                                    @endforeach
                                </select>

                                <script>
                                    new TomSelect("#services", {
                                        plugins: ['remove_button'],
                                        placeholder: "Select services"
                                    });
                                </script>


                                {{-- <link rel="stylesheet"
                                    href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
                                <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

                                <select id="services" name="services[]" multiple>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            {{ in_array($service->id, old('services', $deal->serviceVariants ? $deal->serviceVariants->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                            {{ $service->name }} ({{ $service->price }})
                                        </option>
                                    @endforeach
                                </select>

                                <script>
                                    new Choices('#services', {
                                        removeItemButton: true,
                                        placeholderValue: 'Select services',
                                        searchPlaceholderValue: 'Type to search...'
                                    });
                                </script> --}}




                                <div class="text-end">
                                    <button type="submit" class="btn btn-warning">Update Deal</button>
                                    <a href="{{ route('deals.index') }}" class="btn btn-secondary">Cancel</a>
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

        {{-- Select2 CSS with Bootstrap 5 theme --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />

        {{-- jQuery + Select2 JS --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


        <script>
            $(document).ready(function() {
                $('#services').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Select services",
                    allowClear: true
                });
            });
        </script>

    </div>
    <!-- Content wrapper -->
@endsection
