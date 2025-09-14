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
                            <h5 class="mb-0">All FAQs</h5>
                            <a href="{{ route('faqs.create') }}" class="btn btn-warning">Create</a>
                        </div>
                        <div class="card-body">

                            @include('admin.pages-partials.bulk-actions', [
                                'itemType' => 'faqs',
                                'actions' => [
                                    [
                                        'text' => 'Delete Selected',
                                        'value' => 'delete',
                                        'class' => 'btn-danger',
                                        'url' => route('faqs.bulkDelete'),
                                    ],
                                    [
                                        'text' => 'Mark as Active',
                                        'value' => 'active',
                                        'class' => 'btn-success',
                                        'url' => route('faqs.bulkStatus'),
                                    ],
                                    [
                                        'text' => 'Mark as Inactive',
                                        'value' => 'inactive',
                                        'class' => 'btn-secondary',
                                        'url' => route('faqs.bulkStatus'),
                                    ],
                                ],
                            ])


                            <div class="container">
                                <form action="{{ route('site-settings.update', $setting->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- General Info -->
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header fw-bold">General Info</div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Site Name</label>
                                                        <input type="text" name="site_name" class="form-control"
                                                            value="{{ old('site_name', $setting->site_name) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="contact_email" class="form-control"
                                                            value="{{ old('contact_email', $setting->contact_email) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" name="contact_phone" class="form-control"
                                                            value="{{ old('contact_phone', $setting->contact_phone) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Address</label>
                                                        <textarea name="contact_address" class="form-control" rows="2">{{ old('contact_address', $setting->contact_address) }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Working Hours</label>
                                                        <input type="text" name="working_hours" class="form-control"
                                                            value="{{ old('working_hours', $setting->working_hours) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Currency</label>
                                                        <input type="text" name="currency" class="form-control"
                                                            value="{{ old('currency', $setting->currency ?? 'PKR') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Branding -->
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header fw-bold">Branding</div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Logo</label><br>
                                                        @if ($setting->site_logo && file_exists(public_path('storage/' . $setting->site_logo)))
                                                            <img src="{{ asset('storage/' . $setting->site_logo) }}"
                                                                width="100" class="mb-2">
                                                        @endif
                                                        <input type="file" name="site_logo" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Favicon</label><br>
                                                        @if ($setting->favicon && file_exists(public_path('storage/' . $setting->favicon)))
                                                            <img src="{{ asset('storage/' . $setting->favicon) }}"
                                                                width="30" class="mb-2">
                                                        @endif
                                                        <input type="file" name="favicon" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Default Image</label><br>
                                                        @if ($setting->default_image && file_exists(public_path('storage/' . $setting->default_image)))
                                                            <img src="{{ asset('storage/' . $setting->default_image) }}"
                                                                width="100" class="mb-2">
                                                        @endif
                                                        <input type="file" name="default_image" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Social Media -->
                                        <div class="col-md-12">
                                            <div class="card mb-3">
                                                <div class="card-header fw-bold">Social Media</div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Facebook Link</label>
                                                        <input type="url" name="facebook_link" class="form-control"
                                                            value="{{ old('facebook_link', $setting->facebook_link) }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Instagram Link</label>
                                                        <input type="url" name="instagram_link" class="form-control"
                                                            value="{{ old('instagram_link', $setting->instagram_link) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bx bx-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Media Preview Modal -->
{{-- @include('admin.pages-partials.preview_modal') --}}





<!-- Content wrapper -->
@endsection

@push('scripts')
@include('admin.faqs.js.script')
@endpush
