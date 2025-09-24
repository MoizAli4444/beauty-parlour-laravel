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
                            <h5 class="mb-0">View Expense</h5>
                            <div>

                                {!! render_delete_button($expense->id, route('expenses.destroy', $expense->id), false) !!}
                                {!! render_edit_button(route('expenses.edit', $expense->id), false) !!}
                                {!! render_index_button(route('expenses.index'), 'All Expenses', false) !!}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">

                                <!-- Left Column -->
                                <div class="col-md-8">

                                    <!-- Expense Type -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Expense Type:</label>
                                        <div>{{ $expense->expense_type ?? '-' }}</div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Amount:</label>
                                        <div>{{ $expense->amount ? number_format($expense->amount, 2) . ' PKR' : '-' }}
                                        </div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Payment Method:</label>
                                        <div>{{ ucfirst($expense->payment_method) ?? '-' }}</div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Notes:</label>
                                        <div>{{ $expense->notes ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-4">

                                      

                                        <!-- Date -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Expense Date:</label>
                                            <div>
                                                {{ $expense->date ? \Carbon\Carbon::parse($expense->date)->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>

                                        <!-- Created At -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Created At:</label>
                                            <div>
                                                {{ $expense->created_at ? $expense->created_at->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>

                                        <!-- Updated At -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Last Updated:</label>
                                            <div>
                                                {{ $expense->updated_at ? $expense->updated_at->format('d M Y h:i A') : '—' }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Right Column (Receipt Preview) -->
                                <div class="col-md-4 text-center">
                                    <label class="form-label fw-bold">Receipt Preview:</label><br>

                                    {!! getImage($expense->receipt_path, true) !!}
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
