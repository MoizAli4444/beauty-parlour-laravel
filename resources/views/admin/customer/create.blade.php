@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xl">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Create Customer</h5>
                            <a href="{{ route('customers.index') }}" class="btn btn-warning">All Customers</a>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">

                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="name">Customer Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            value="{{ old('name') }}" placeholder="Enter customer name">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="{{ old('email') }}" placeholder="Enter email">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control" id="phone"
                                            value="{{ old('phone') }}" placeholder="Enter phone number">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="image">Profile Image</label>
                                        <input type="file" name="image" class="form-control" id="image">
                                        @error('image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="date_of_birth">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth"
                                            value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="gender">Gender</label>
                                        <select name="gender" class="form-control" id="gender">
                                            <option value="">-- Select Gender --</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                  

                                    <div class="mb-4 col-12 col-md-4">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ old('city') }}">
                                    </div>

                                    {{-- <div class="mb-4 col-12 col-md-4">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" name="country" class="form-control"
                                            value="{{ old('country', 'Pakistan') }}">
                                    </div> --}}

                                    <div class="mb-4 col-12 col-md-4">
                                        <label for="postal_code" class="form-label">Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control"
                                            value="{{ old('postal_code') }}">
                                    </div>


                                    <div class="mb-4 col-12 col-md-4">
                                        <label class="form-label" for="status">Status</label>
                                        <select name="status" class="form-select" id="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                      <div class="mb-4 col-12 ">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                                    </div>

                                    <!-- Password Field (if applicable) -->
                                    {{-- <div class="mb-4 col-12 col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div> --}}


                                </div>

                                
                                <button type="submit" class="btn btn-warning">Create Customer</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
