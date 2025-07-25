@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xl">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edit Customer</h5>
                            <a href="{{ route('customers.index') }}" class="btn btn-primary">All Customers</a>
                        </div>

                        <div class="card-body">
                            {{-- <form action="{{ route('customers.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label" for="name">Customer Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ old('name', $user->name) }}" placeholder="Enter customer name">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email', $user->email) }}" placeholder="Enter email">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control" id="phone"
                                    value="{{ old('phone', $user->customer->phone) }}" placeholder="Enter phone number">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="image">Profile Image</label>
                                <input type="file" name="image" class="form-control" id="image">
                                @if ($user->customer->image)
                                    <img src="{{ asset('storage/' . $user->customer->image) }}" alt="Current Image" width="100" class="mt-2">
                                @endif
                                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" name="address" class="form-control" id="address"
                                    value="{{ old('address', $user->customer->address) }}" placeholder="Enter address">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="city">City</label>
                                <input type="text" name="city" class="form-control" id="city"
                                    value="{{ old('city', $user->customer->city) }}" placeholder="Enter city">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="country">Country</label>
                                <input type="text" name="country" class="form-control" id="country"
                                    value="{{ old('country', $user->customer->country) }}" placeholder="Enter country">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="postal_code">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" id="postal_code"
                                    value="{{ old('postal_code', $user->customer->postal_code) }}" placeholder="Enter postal code">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="date_of_birth">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" id="date_of_birth"
                                    value="{{ old('date_of_birth', $user->customer->date_of_birth) }}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="gender">Gender</label>
                                <select name="gender" class="form-select" id="gender">
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('gender', $user->customer->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->customer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->customer->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="status">Status</label>
                                <select name="status" class="form-select" id="status">
                                    <option value="active" {{ $user->customer->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $user->customer->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="email_verified" id="email_verified" value="1"
                                    {{ $user->customer->email_verified ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verified">Email Verified</label>
                            </div>

                            <button type="submit" class="btn btn-success">Update Customer</button>
                        </form> --}}

                            <form action="{{ route('customers.update', $user->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    {{-- Name --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="name">Customer Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            value="{{ old('name', $user->name) }}" placeholder="Enter customer name">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="{{ old('email', $user->email) }}" placeholder="Enter email">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Phone --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control" id="phone"
                                            value="{{ old('phone', $user->customer->phone) }}"
                                            placeholder="Enter phone number">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Profile Image --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="image">Profile Image</label>
                                        <input type="file" name="image" class="form-control" id="image">
                                        @if ($user->customer->image)
                                            <img src="{{ asset('storage/' . $user->customer->image) }}" alt="Current Image"
                                                width="100" class="mt-2">
                                        @endif
                                        @error('image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Date of Birth --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="date_of_birth">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth"
                                            value="{{ old('date_of_birth', $user->customer->date_of_birth) }}">
                                        @error('date_of_birth')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Gender --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="gender">Gender</label>
                                        <select name="gender" class="form-select" id="gender">
                                            <option value="">Select gender</option>
                                            <option value="male"
                                                {{ old('gender', $user->customer->gender) == 'male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="female"
                                                {{ old('gender', $user->customer->gender) == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="other"
                                                {{ old('gender', $user->customer->gender) == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- City --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="city">City</label>
                                        <input type="text" name="city" class="form-control" id="city"
                                            value="{{ old('city', $user->customer->city) }}" placeholder="Enter city">
                                    </div>

                                    {{-- Postal Code --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="postal_code">Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control" id="postal_code"
                                            value="{{ old('postal_code', $user->customer->postal_code) }}"
                                            placeholder="Enter postal code">
                                    </div>

                                    {{-- Status --}}
                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="status">Status</label>
                                        <select name="status" class="form-select" id="status">
                                            <option value="active"
                                                {{ $user->customer->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive"
                                                {{ $user->customer->status == 'inactive' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    

                                    {{-- Address --}}
                                    <div class="mb-4 col-12">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea name="address" class="form-control" id="address">{{ old('address', $user->customer->address) }}</textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">Update Customer</button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
