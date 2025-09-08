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
                                                <img src="{{ $customer->profile_image ?? 'https://via.placeholder.com/120' }}"
                                                    class="rounded-circle shadow-sm" width="120" height="120"
                                                    alt="Customer Image">
                                                <h5 class="mt-3">{{ $customer->name }}</h5>
                                                <span
                                                    class="badge bg-success">{{ ucfirst($customer->status ?? 'active') }}</span>
                                            </div>

                                            {{-- Details Table --}}
                                            <div class="table-responsive">
                                                <table class="table table-striped table-borderless align-middle">
                                                    <tbody>
                                                        <tr>
                                                            <th width="30%">Email</th>
                                                            <td>{{ $customer->email ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone</th>
                                                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Address</th>
                                                            <td>{{ $customer->address ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Created At</th>
                                                            <td>{{ $customer->created_at?->format('d M Y, h:i A') }}</td>
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

                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Deal Name:</label>
                                        <div>{{ $deal->name ?? '-' }}</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description:</label>
                                        <div>{{ $deal->description ?? '-' }}</div>
                                    </div>

                                    <!-- Price & Services Total -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Price:</label>
                                            <div class="text-primary fw-semibold">
                                                Rs {{ number_format($deal->price, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Services Total:</label>
                                            <div>
                                                Rs {{ number_format($deal->services_total ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dates -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Start Date:</label>
                                            <div>
                                                {{ $deal->start_date ? \Carbon\Carbon::parse($deal->start_date)->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">End Date:</label>
                                            <div>
                                                {{ $deal->end_date ? \Carbon\Carbon::parse($deal->end_date)->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Status:</label>
                                        <div>{!! $deal->status_badge !!}</div>
                                    </div>

                                    <!-- Services -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Services:</label>
                                        <div>
                                            @forelse($deal->serviceVariants as $service)
                                                <span class="badge bg-info text-dark me-1 mb-1">
                                                    {{ $service->name }} (Rs {{ number_format($service->price, 2) }})
                                                </span>
                                            @empty
                                                <span>—</span>
                                            @endforelse
                                        </div>
                                    </div>


                                </div>

                                <!-- Right Column (Image Preview) -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold">Media Preview:</label><br>

                                    @if ($deal->image && file_exists(public_path('storage/' . $deal->image)))
                                        <img src="{{ asset('storage/' . $deal->image) }}" alt="{{ $deal->name }}"
                                            class="img-fluid rounded shadow-sm mb-2 js-media-preview"
                                            style="max-height:200px; object-fit:cover;"
                                            data-url="{{ asset('storage/' . $deal->image) }}" data-type="image">
                                    @else
                                        <div class="text-muted">No image uploaded</div>
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
