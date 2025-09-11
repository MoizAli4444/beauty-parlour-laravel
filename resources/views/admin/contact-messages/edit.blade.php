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

                            <form action="{{ route('contact-messages.update', $message->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" value="{{ $message->name }}" disabled>
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" value="{{ $message->email }}" disabled>
                                </div>

                                {{-- Phone --}}
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" value="{{ $message->phone }}" disabled>
                                </div>

                                {{-- Subject --}}
                                <div class="mb-3">
                                    <label class="form-label">Subject</label>
                                    <input type="text" class="form-control" value="{{ $message->subject }}" disabled>
                                </div>

                                {{-- Message --}}
                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control" rows="3" disabled>{{ $message->message }}</textarea>
                                </div>

                                {{-- Response (Editable) --}}
                                <div class="mb-3">
                                    <label class="form-label">Response</label>
                                    <textarea name="response" class="form-control @error('response') is-invalid @enderror" rows="4">{{ old('response', $message->response) }}</textarea>
                                    @error('response')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="open"
                                            {{ old('status', $message->status) == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress"
                                            {{ old('status', $message->status) == 'in_progress' ? 'selected' : '' }}>In
                                            Progress</option>
                                        <option value="closed"
                                            {{ old('status', $message->status) == 'closed' ? 'selected' : '' }}>Closed
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Priority --}}
                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                        <option value="low"
                                            {{ old('priority', $message->priority) == 'low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="medium"
                                            {{ old('priority', $message->priority) == 'medium' ? 'selected' : '' }}>Medium
                                        </option>
                                        <option value="high"
                                            {{ old('priority', $message->priority) == 'high' ? 'selected' : '' }}>High
                                        </option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Received At (readonly) --}}
                                <div class="mb-3">
                                    <label class="form-label">Received At</label>
                                    <input type="text" class="form-control"
                                        value="{{ $message->created_at?->format('d M Y h:i A') }}" disabled>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Update Message</button>
                                    <a href="{{ route('contact-messages.index') }}" class="btn btn-secondary">Cancel</a>
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
