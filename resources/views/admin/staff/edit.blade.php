@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($staff) ? 'Update' : 'Create' }} Staff Member</h2>
    <form action="{{ isset($staff) ? route('staff.update', $staff->id) : route('staff.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($staff))
            @method('PUT')
        @endif

        <!-- User Info -->
        <div class="row">
            <div class="col-md-6">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $staff->user->name ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $staff->user->email ?? '') }}" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label>Password {{ isset($staff) ? '(Leave blank to keep current)' : '' }}</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone ?? '') }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label>Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $staff->address ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $staff->date_of_birth ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Joining Date</label>
                <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $staff->joining_date ?? '') }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label>Leaving Date</label>
                <input type="date" name="leaving_date" class="form-control" value="{{ old('leaving_date', $staff->leaving_date ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>CNIC</label>
                <input type="text" name="cnic" class="form-control" value="{{ old('cnic', $staff->cnic ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Emergency Contact</label>
                <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $staff->emergency_contact ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Profile Image</label>
                <input type="file" name="image" class="form-control">
                @if(isset($staff->image))
                    <img src="{{ asset('storage/' . $staff->image) }}" width="50" class="mt-2">
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label>Staff Role</label>
                <select name="staff_role_id" class="form-control">
                    @foreach($staffRoles as $role)
                        <option value="{{ $role->id }}" {{ old('staff_role_id', $staff->staff_role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Shift</label>
                <select name="shift_id" class="form-control">
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $staff->shift_id ?? '') == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Payment Method</label>
                <select name="payment_method_id" class="form-control">
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}" {{ old('payment_method_id', $staff->payment_method_id ?? '') == $method->id ? 'selected' : '' }}>{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Bank Account Number</label>
                <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $staff->bank_account_number ?? '') }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label>Salary</label>
                <input type="number" name="salary" step="0.01" class="form-control" value="{{ old('salary', $staff->salary ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>Payment Schedule</label>
                <select name="payment_schedule" class="form-control">
                    <option value="monthly" {{ old('payment_schedule', $staff->payment_schedule ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="weekly" {{ old('payment_schedule', $staff->payment_schedule ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="daily" {{ old('payment_schedule', $staff->payment_schedule ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Working Days</label>
                <select name="working_days[]" class="form-control" multiple>
                    @foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)
                        <option value="{{ $day }}" {{ in_array($day, old('working_days', json_decode($staff->working_days ?? '[]'))) ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ old('status', $staff->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $staff->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="is_head" value="1" {{ old('is_head', $staff->is_head ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Is Head</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_verified" value="1" {{ old('is_verified', $staff->is_verified ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Is Verified</label>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">{{ isset($staff) ? 'Update' : 'Create' }}</button>
        </div>
    </form>
</div>
@endsection
