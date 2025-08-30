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
                            <h5 class="mb-0">View Customer</h5>
                            <div>
                                {!! render_delete_button($user->id, route('customers.destroy', $user->id), false) !!}
                                {!! render_edit_button(route('customers.edit', $user->slug), false) !!}
                                {!! render_index_button(route('customers.index'), 'All Customers', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-8">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Name:</label>
                                            <div>{{ $user->name }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Email:</label>
                                            <div>{{ $user->email }}</div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Phone:</label>
                                            <div>{{ $user->customer->phone }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Date of Birth:</label>
                                            <div>{{ $user->customer->date_of_birth }}</div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Gender:</label>
                                            <div>{{ ucfirst($user->customer->gender) }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">City:</label>
                                            <div>{{ $user->customer->city ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Postal Code:</label>
                                            <div>{{ $user->customer->postal_code ?? '-' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Status:</label>
                                            <div>{!! $user->customer->status_badge !!}</div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Address:</label>
                                        <div>{{ $user->customer->address ?? '-' }}</div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Created At:</label>
                                        <div>{{ $user->customer->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>

                                <!-- Right Column: Image -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Customer Image:</label><br>
                                    @if (!empty($user->customer->image) && file_exists(public_path('storage/' . $user->customer->image)))
                                        <img src="{{ asset('storage/' . $user->customer->image) }}" alt="Customer Image"
                                            data-url="{{ asset('storage/' . $user->customer->image) }}" data-type="image"
                                            class="rounded shadow-sm js-media-preview" style="width: 250px; object-fit: cover;">
                                    @else
                                        <div class="text-muted">No image uploaded</div>
                                    @endif
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
