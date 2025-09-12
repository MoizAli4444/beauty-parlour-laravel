@extends('admin.layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
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
                            <h5 class="mb-0">Edit FAQ</h5>

                            <div>
                                {!! render_delete_button($faq->id, route('faqs.destroy', $faq->id), false) !!}
                                {{-- {!! render_view_button(route('faqs.show', $faq->slug), false) !!} --}}
                                {!! render_index_button(route('faqs.index'), 'All FAQs', false) !!}

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

                            @extends('layouts.app')

                        @section('content')
                            <div class="container">
                                <h3 class="mb-4">Edit Testimonial</h3>

                                <form action="{{ route('testimonials.update', $testimonial->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Name --}}
                                    <div class="mb-3">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $testimonial->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Designation --}}
                                    <div class="mb-3">
                                        <label class="form-label">Designation</label>
                                        <input type="text" name="designation"
                                            class="form-control @error('designation') is-invalid @enderror"
                                            value="{{ old('designation', $testimonial->designation) }}">
                                        @error('designation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Testimonial --}}
                                    <div class="mb-3">
                                        <label class="form-label">Testimonial <span class="text-danger">*</span></label>
                                        <textarea name="testimonial" rows="5" class="form-control @error('testimonial') is-invalid @enderror" required>{{ old('testimonial', $testimonial->testimonial) }}</textarea>
                                        @error('testimonial')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Image --}}
                                    <div class="mb-3">
                                        <label class="form-label">Image</label>
                                        @if ($testimonial->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="Image"
                                                    class="img-thumbnail" style="max-width: 150px;">
                                            </div>
                                        @endif
                                        <input type="file" name="image"
                                            class="form-control @error('image') is-invalid @enderror">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                                            <option value="pending"
                                                {{ old('status', $testimonial->status) == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="active"
                                                {{ old('status', $testimonial->status) == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $testimonial->status) == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Submit --}}
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        @endsection



                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- / Content -->

    <!-- Media Preview Modal -->
    @include('admin.pages-partials.preview_modal')



</div>
<!-- Content wrapper -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
    let serviceSelect = new TomSelect("#services", {
        plugins: ['remove_button'],
        placeholder: "Select services"
    });

    function updateTotal() {
        let total = 0;
        let selectedOptions = serviceSelect.getValue();

        selectedOptions.forEach(id => {
            let option = document.querySelector(`#services option[value="${id}"]`);
            if (option) {
                total += parseFloat(option.getAttribute('data-price')) || 0;
            }
        });

        document.getElementById('services_total').value = total.toFixed(2);
    }

    // Run when changed
    serviceSelect.on('change', updateTotal);

    // Run on page load (in case of edit with pre-selected services)
    updateTotal();
</script>
@endpush
