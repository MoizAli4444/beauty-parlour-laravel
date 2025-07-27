@extends('admin.layouts.app')

@push('scripts')
    @include('admin.staff.js.css')
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
                            <h5 class="mb-0">Edit Staff</h5>
                            <div>
                                {!! render_delete_button($user->id, route('staff.destroy', $user->id), false) !!}
                                {!! render_view_button(route('staff.show', $user->slug), false) !!}
                                {!! render_index_button(route('staff.index'), 'All Staff', false) !!}
                            </div>
                        </div>

                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <form action="{{ route('staff.update', $user->id)}}"
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
                                            value="{{ old('name', $user->name ?? '') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $user->email ?? '') }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    
                                    {{-- <div class="col-12 col-md-4 mb-3">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                        @error('password_confirmation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div> --}}
                                    

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone', $user->staff->phone ?? '') }}">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" class="form-control"
                                            value="{{ old('address', $user->staff->address ?? '') }}">
                                        @error('address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        
                                        <label for="date_of_birth">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control"
                                            value="{{ old('date_of_birth', $user->staff->date_of_birth?->format('Y-m-d')) }}"
>
                                        @error('date_of_birth')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="joining_date">Joining Date</label>
                                        <input type="date" name="joining_date" class="form-control"
                                            value="{{ old('joining_date', $user->staff->joining_date?->format('Y-m-d')) }}"
>
                                        @error('joining_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="leaving_date">Leaving Date</label>
                                        <input type="date" name="leaving_date" class="form-control"
                                            value="{{ old('leaving_date', $user->staff->leaving_date?->format('Y-m-d')) }}">
                                        @error('leaving_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="cnic">CNIC</label>
                                        <input type="text" name="cnic" class="form-control"
                                            value="{{ old('cnic', $user->staff->cnic ?? '') }}">
                                        @error('cnic')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="emergency_contact">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" class="form-control"
                                            value="{{ old('emergency_contact', $user->staff->emergency_contact ?? '') }}">
                                        @error('emergency_contact')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control">
                                        @error('image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @if (isset($staff) && $user->staff->image)
                                            <img src="{{ asset('storage/' . $user->staff->image) }}" alt="Profile"
                                                width="80">
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="shift_id">Shift</label>
                                        <select name="shift_id" class="form-control">
                                            <option value="">Select Shift</option>
                                            @foreach ($shifts as $shift)
                                                <option value="{{ $shift->id }}"
                                                    {{ old('shift_id', $user->staff->shift_id ?? '') == $shift->id ? 'selected' : '' }}>
                                                    {{ $shift->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('shift_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="working_days[]">Working Days</label>
                                        {{-- <select name="working_days[]" class="form-control" multiple> --}}
                                        <select name="working_days[]" class="form-control select2-multiple" multiple
                                            data-placeholder="Select working days">
                                            @php $days = ['mon','tue','wed','thu','fri','sat','sun']; @endphp
                                            @foreach ($days as $day)
                                                <option value="{{ $day }}"
                                                    {{ isset($staff) && in_array($day, json_decode($user->staff->working_days ?? '[]')) ? 'selected' : '' }}>
                                                    {{ ucfirst($day) }}</option>
                                            @endforeach
                                        </select>
                                        @error('working_days')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="salary">Salary</label>
                                        <input type="number" step="0.01" name="salary" class="form-control"
                                            value="{{ old('salary', $user->staff->salary ?? '') }}">
                                        @error('salary')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="payment_schedule">Payment Schedule</label>
                                        <select name="payment_schedule" class="form-control">
                                            <option value="monthly"
                                                {{ old('payment_schedule', $user->staff->payment_schedule ?? '') == 'monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                            <option value="weekly"
                                                {{ old('payment_schedule', $user->staff->payment_schedule ?? '') == 'weekly' ? 'selected' : '' }}>
                                                Weekly</option>
                                        </select>
                                        @error('payment_schedule')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="payment_method_id">Payment Method</label>
                                        <select name="payment_method_id" class="form-control">
                                            <option value="">Select Method</option>
                                            @foreach ($payment_methods as $method)
                                                <option value="{{ $method->id }}"
                                                    {{ old('payment_method_id', $user->staff->payment_method_id ?? '') == $method->id ? 'selected' : '' }}>
                                                    {{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('payment_method_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="bank_account_number">Bank Account #</label>
                                        <input type="text" name="bank_account_number" class="form-control"
                                            value="{{ old('bank_account_number', $user->staff->bank_account_number ?? '') }}">
                                        @error('bank_account_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-12 col-md-4 mb-3">
                                        <label for="is_verified">Is Verified</label>
                                        <select name="is_verified" class="form-control">
                                            <option value="0"
                                                {{ old('is_verified', $user->staff->is_verified ?? 0) == 0 ? 'selected' : '' }}>
                                                No</option>
                                            <option value="1"
                                                {{ old('is_verified', $user->staff->is_verified ?? 0) == 1 ? 'selected' : '' }}>
                                                Yes</option>
                                        </select>
                                    </div> --}}

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="active"
                                                {{ old('status', $user->staff->status ?? '') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $user->staff->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="is_head">Is Head</label>
                                        <select name="is_head" class="form-control">
                                            <option value="0"
                                                {{ old('is_head', $user->staff->is_head ?? 0) == 0 ? 'selected' : '' }}>No
                                            </option>
                                            <option value="1"
                                                {{ old('is_head', $user->staff->is_head ?? 0) == 1 ? 'selected' : '' }}>Yes
                                            </option>
                                        </select>
                                        @error('is_head')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="staff_role_id">Staff Role</label>
                                        <select name="staff_role_id" class="form-control">
                                            <option value="">Select Role</option>
                                            @foreach ($staff_roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('staff_role_id', $user->staff->staff_role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('staff_role_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit"
                                        class="btn btn-warning">Update</button>
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

@push('scripts')
    @include('admin.staff.js.script')
@endpush
