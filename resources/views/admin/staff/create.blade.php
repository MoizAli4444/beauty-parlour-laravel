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
                            <h5 class="mb-0">Create Service</h5>
                            <a href="{{ route('services.index') }}" class="btn btn-primary">All Services</a>
                        </div>

                        <div class="card-body">

                            <form action="{{ isset($staff) ? route('staff.update', $staff->id) : route('staff.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (isset($staff))
                                    @method('PUT')
                                @endif

                                <!-- User Info -->
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $staff->user->name ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $staff->user->email ?? '') }}">
                                    </div>

                                    @if (!isset($staff))
                                        <div class="col-12 col-md-4 mb-3">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>

                                        <div class="col-12 col-md-4 mb-3">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                    @endif
                                    {{-- </div>

        <!-- Staff Info -->
        <div class="row"> --}}
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone', $staff->phone ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" class="form-control"
                                            value="{{ old('address', $staff->address ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="date_of_birth">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control"
                                            value="{{ old('date_of_birth', $staff->date_of_birth ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="joining_date">Joining Date</label>
                                        <input type="date" name="joining_date" class="form-control"
                                            value="{{ old('joining_date', $staff->joining_date ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="leaving_date">Leaving Date</label>
                                        <input type="date" name="leaving_date" class="form-control"
                                            value="{{ old('leaving_date', $staff->leaving_date ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="cnic">CNIC</label>
                                        <input type="text" name="cnic" class="form-control"
                                            value="{{ old('cnic', $staff->cnic ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="emergency_contact">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" class="form-control"
                                            value="{{ old('emergency_contact', $staff->emergency_contact ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control">
                                        @if (isset($staff) && $staff->image)
                                            <img src="{{ asset('storage/' . $staff->image) }}" alt="Profile"
                                                width="80">
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="shift_id">Shift</label>
                                        <select name="shift_id" class="form-control">
                                            <option value="">Select Shift</option>
                                            @foreach ($shifts as $shift)
                                                <option value="{{ $shift->id }}"
                                                    {{ old('shift_id', $staff->shift_id ?? '') == $shift->id ? 'selected' : '' }}>
                                                    {{ $shift->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="working_days[]">Working Days</label>
                                        <select name="working_days[]" class="form-control" multiple>
                                            @php $days = ['mon','tue','wed','thu','fri','sat','sun']; @endphp
                                            @foreach ($days as $day)
                                                <option value="{{ $day }}"
                                                    {{ isset($staff) && in_array($day, json_decode($staff->working_days ?? '[]')) ? 'selected' : '' }}>
                                                    {{ ucfirst($day) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="salary">Salary</label>
                                        <input type="number" step="0.01" name="salary" class="form-control"
                                            value="{{ old('salary', $staff->salary ?? '') }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="payment_schedule">Payment Schedule</label>
                                        <select name="payment_schedule" class="form-control">
                                            <option value="monthly"
                                                {{ old('payment_schedule', $staff->payment_schedule ?? '') == 'monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                            <option value="weekly"
                                                {{ old('payment_schedule', $staff->payment_schedule ?? '') == 'weekly' ? 'selected' : '' }}>
                                                Weekly</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="payment_method_id">Payment Method</label>
                                        <select name="payment_method_id" class="form-control">
                                            <option value="">Select Method</option>
                                            @foreach ($payment_methods as $method)
                                                <option value="{{ $method->id }}"
                                                    {{ old('payment_method_id', $staff->payment_method_id ?? '') == $method->id ? 'selected' : '' }}>
                                                    {{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="bank_account_number">Bank Account #</label>
                                        <input type="text" name="bank_account_number" class="form-control"
                                            value="{{ old('bank_account_number', $staff->bank_account_number ?? '') }}">
                                    </div>

                                    {{-- <div class="col-12 col-md-4 mb-3">
                                        <label for="is_verified">Is Verified</label>
                                        <select name="is_verified" class="form-control">
                                            <option value="0"
                                                {{ old('is_verified', $staff->is_verified ?? 0) == 0 ? 'selected' : '' }}>
                                                No</option>
                                            <option value="1"
                                                {{ old('is_verified', $staff->is_verified ?? 0) == 1 ? 'selected' : '' }}>
                                                Yes</option>
                                        </select>
                                    </div> --}}

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="active"
                                                {{ old('status', $staff->status ?? '') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $staff->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="is_head">Is Head</label>
                                        <select name="is_head" class="form-control">
                                            <option value="0"
                                                {{ old('is_head', $staff->is_head ?? 0) == 0 ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1"
                                                {{ old('is_head', $staff->is_head ?? 0) == 1 ? 'selected' : '' }}>Yes
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="staff_role_id">Staff Role</label>
                                        <select name="staff_role_id" class="form-control">
                                            <option value="">Select Role</option>
                                            @foreach ($staff_roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('staff_role_id', $staff->staff_role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit"
                                        class="btn btn-primary">{{ isset($staff) ? 'Update' : 'Create' }}</button>
                                </div>
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
