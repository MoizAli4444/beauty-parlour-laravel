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


                            <div class="table-responsive">
                                <table id="indexsite-settingsTable" class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Site Name</th>
                                            <th>Logo</th>
                                            <th>Favicon</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Working Hours</th>
                                            <th>Facebook</th>
                                            <th>Instagram</th>
                                            <th>Currency</th>
                                            <th>Default Image</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($settings as $setting)
                                            <tr>
                                                <td>{{ $setting->id }}</td>
                                                <td>{{ $setting->site_name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($setting->site_logo && file_exists(public_path('storage/' . $setting->site_logo)))
                                                        <img src="{{ asset('storage/' . $setting->site_logo) }}"
                                                            width="50" height="50">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($setting->favicon && file_exists(public_path('storage/' . $setting->favicon)))
                                                        <img src="{{ asset('storage/' . $setting->favicon) }}"
                                                            width="30" height="30">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $setting->contact_email ?? 'N/A' }}</td>
                                                <td>{{ $setting->contact_phone ?? 'N/A' }}</td>
                                                <td>{{ $setting->contact_address ?? 'N/A' }}</td>
                                                <td>{{ $setting->working_hours ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($setting->facebook_link)
                                                        <a href="{{ $setting->facebook_link }}" target="_blank">Facebook</a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($setting->instagram_link)
                                                        <a href="{{ $setting->instagram_link }}"
                                                            target="_blank">Instagram</a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $setting->currency ?? 'PKR' }}</td>
                                                <td>
                                                    @if ($setting->default_image && file_exists(public_path('storage/' . $setting->default_image)))
                                                        <img src="{{ asset('storage/' . $setting->default_image) }}"
                                                            width="50" height="50">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $setting->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('site-settings.edit', $setting->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form action="{{ route('site-settings.destroy', $setting->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="14" class="text-center">No site settings found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
