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
                            <h5 class="mb-0">View Message</h5>
                            <div>

                                {!! render_delete_button($message->id, route('contact-messages.destroy', $message->id), false) !!}
                                {!! render_edit_button(route('contact-messages.edit', $message->id), false) !!}
                                {!! render_index_button(route('contact-messages.index'), 'All Messages', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">

                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Name -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Name:</label>
                                        <div>{{ $message->name ?? '-' }}</div>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Email:</label>
                                        <div>{{ $message->email ?? '-' }}</div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Phone:</label>
                                        <div>{{ $message->phone ?? '-' }}</div>
                                    </div>

                                    <!-- Subject -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Subject:</label>
                                        <div>{{ $message->subject ?? '-' }}</div>
                                    </div>

                                    <!-- Message -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Message:</label>
                                        <div class="p-3 bg-light rounded shadow-sm">
                                            {{ $message->message ?? '-' }}
                                        </div>
                                    </div>

                                    <!-- Received At -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Received At:</label>
                                        <div>{{ $message->created_at?->format('d M Y h:i A') }}</div>
                                    </div>
                                </div>

                                <!-- Right Column (Optional Preview or Status) -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold">Status:</label><br>
                                    @if ($message->is_read)
                                        <span class="badge bg-success">Read</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Unread</span>
                                    @endif
                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Media Preview Modal -->
            @include('admin.pages-partials.preview_modal')

        </div>
    @endsection
