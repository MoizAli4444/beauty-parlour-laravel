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
                            <h5 class="mb-0">View Deal</h5>
                            <div>

                                {!! render_delete_button($deal->id, route('deals.destroy', $deal->id), false) !!}
                                {!! render_edit_button(route('deals.edit', $deal->slug), false) !!}
                                {!! render_index_button(route('deals.index'), 'All Deals', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">

                                    {{-- Card --}}
                                    <div class="card shadow-lg border-0 rounded-3">
                                        <div class="card-header bg-primary text-white text-center">
                                            <h4 class="mb-0">Customer Details</h4>
                                        </div>
                                        <div class="card-body p-4">

                                            {{-- Profile --}}
                                            <div class="text-center mb-4">
                                                <img src="{{ $message->profile_image ?? 'https://via.placeholder.com/120' }}"
                                                    class="rounded-circle shadow-sm" width="120" height="120"
                                                    alt="Customer Image">
                                                <h5 class="mt-3">{{ $message->name }}</h5>
                                                <span
                                                    class="badge bg-success">{{ ucfirst($message->status ?? 'active') }}</span>
                                            </div>

                                            {{-- Details Table --}}
                                            <div class="table-responsive">
                                                <table class="table table-striped table-borderless align-middle">
                                                    <tbody>
                                                        <tr>
                                                            <th width="30%">Email</th>
                                                            <td>{{ $message->email ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone</th>
                                                            <td>{{ $message->phone ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Address</th>
                                                            <td>{{ $message->address ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Created At</th>
                                                            <td>{{ $message->created_at?->format('d M Y, h:i A') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Updated At</th>
                                                            <td>{{ $customer->updated_at?->format('d M Y, h:i A') }}</td>
                                                        </tr>
                                                        {{-- Add more fields if needed --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="card-footer text-center bg-light">
                                            <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                                                <i class="bi bi-arrow-left"></i> Back
                                            </a>
                                            <a href="{{ route('customers.edit', $customer->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this customer?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-lg-10">

                                    <div class="card shadow-lg border-0 rounded-3">
                                        <div class="card-header bg-primary text-white">
                                            <h4 class="mb-0">Contact Message Details</h4>
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

                                        <div class="card-footer text-center bg-light">
                                            <a href="{{ route('contact-messages.index') }}"
                                                class="btn btn-secondary btn-sm">
                                                <i class="bi bi-arrow-left"></i> Back
                                            </a>
                                            <form action="{{ route('contact-messages.destroy', $message->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this message?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>

                                    </div>
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
