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
                            <h5 class="mb-0">View Staff</h5>
                            <div>

                                {!! render_delete_button($user->id, route('staff.destroy', $user->id), false) !!}
                                {!! render_edit_button(route('staff.edit', $user->slug), false) !!}
                                {!! render_index_button(route('staff.index'), 'All Staff', false) !!}
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

                                    <!-- Left Column: Staff Details -->
                                    <div class="row mb-4">

                                        <!-- Address -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Address:</label>
                                            <div>{{ $user->staff->address ?? '-' }}</div>
                                        </div>

                                    </div>
                                    <!-- Dates Row -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Phone:</label>
                                            <div>{{ $user->staff->phone ?? '-' }}</div>
                                        </div>


                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Date of Birth:</label>
                                            <div>{{ $user->staff->date_of_birth?->format('d M Y') ?? '-' }}</div>
                                        </div>

                                    </div>



                                    <!-- CNIC & Emergency Contact -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">CNIC:</label>
                                            <div>{{ $user->staff->cnic ?? '-' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Emergency Contact:</label>
                                            <div>{{ $user->staff->emergency_contact ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <!-- Shift & Working Days -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Shift:</label>
                                            <div>{{ $user->staff->shift?->name ?? '-' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Working Days:</label>
                                            <div>

                                                @if ($user->staff->working_days)
                                                    {{ implode(', ', $user->staff->working_days) }}
                                                @else
                                                    -
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Salary & Payment Info -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Salary:</label>
                                            <div>
                                                {{ $user->staff->salary ? 'PKR ' . number_format($user->staff->salary, 2) : '-' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Payment Schedule:</label>
                                            <div>{{ ucfirst($user->staff->payment_schedule) }}</div>
                                        </div>
                                    </div>


                                </div>

                                <!-- Right Column: Profile Image -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Profile Photo:</label><br>
                                    @if (!empty($user->staff->image) && file_exists(public_path('storage/' . $user->staff->image)))
                                        <img src="{{ asset('storage/' . $user->staff->image) }}" alt="Staff Image"
                                         data-url="{{ asset('storage/' . $user->staff->image) }}" data-type="image"
                                            class="rounded shadow-sm js-media-preview" style="width: 250px; object-fit: cover;">
                                    @else
                                        <div class="text-muted">No image uploaded</div>
                                    @endif
                                </div>
                            </div>


                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Payment Method:</label>
                                    <div>{{ $user->staff->paymentMethod?->name ?? '-' }}</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Bank Account #:</label>
                                    <div>{{ $user->staff->bank_account_number ?? '-' }}</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Is Head:</label>
                                    <div>{{ $user->staff->is_head ? 'Yes' : 'No' }}</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Verified:</label>
                                    <div>{{ $user->staff->is_verified ? 'Yes' : 'No' }}</div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Joining Date:</label>
                                    <div>{{ $user->staff->joining_date?->format('d M Y') ?? '-' }}</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Leaving Date:</label>
                                    <div>{{ $user->staff->leaving_date?->format('d M Y') ?? '-' }}</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Status:</label>
                                    <div>{{ ucfirst($user->staff->status) }}</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Created At:</label>
                                    <div>{{ $user->staff->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->

         <!-- Media Preview Modal -->
            @include('admin.galleries.partials.preview_modal')

    </div>
@endsection
