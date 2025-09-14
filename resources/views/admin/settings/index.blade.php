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
                                <table id="indexsite-settingsTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th> {{-- universal checkbox --}}
                                            <th>ID</th>
                                            <th>Setting Key</th>
                                            <th>Setting Value</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($settings as $setting)
                                            <tr>
                                                <td><input type="checkbox" name="selected[]" value="{{ $setting->id }}">
                                                </td>
                                                <td>{{ $setting->id }}</td>
                                                <td>{{ $setting->key }}</td>
                                                <td>
                                                    {{-- if value is image --}}
                                                    @if (Str::endsWith($setting->value, ['.jpg', '.jpeg', '.png', '.gif']))
                                                        <img src="{{ asset('storage/' . $setting->value) }}"
                                                            alt="setting image" width="50" height="50">
                                                    @else
                                                        {{ $setting->value ?? 'N/A' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $setting->status == 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($setting->status) }}
                                                    </span>
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
                                                <td colspan="7" class="text-center">No site settings found</td>
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
