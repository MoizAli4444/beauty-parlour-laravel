@extends('admin.layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
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
                            <h5 class="mb-0">Edit Expense</h5>

                            <div>
                                {!! render_delete_button($expense->id, route('expenses.destroy', $expense->id), false) !!}
                                {!! render_view_button(route('expenses.show', $expense->id), false) !!}
                                {!! render_index_button(route('expenses.index'), 'All Expenses', false) !!}

                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('expenses.update', $expense->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Expense Type --}}
                                <div class="mb-3">
                                    <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                                    <input type="text" name="expense_type"
                                        class="form-control @error('expense_type') is-invalid @enderror"
                                        value="{{ old('expense_type', $expense->expense_type) }}" required>
                                    @error('expense_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Amount --}}
                                <div class="mb-3">
                                    <label class="form-label">Amount (PKR) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount', $expense->amount) }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Payment Method --}}
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="cash"
                                            {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>
                                            Cash</option>
                                        <option value="cheque"
                                            {{ old('payment_method', $expense->payment_method) == 'cheque' ? 'selected' : '' }}>
                                            Cheque</option>
                                        <option value="online_payment"
                                            {{ old('payment_method', $expense->payment_method) == 'online_payment' ? 'selected' : '' }}>
                                            Online Payment</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Date --}}
                                <div class="mb-3">
                                    <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date"
                                        class="form-control @error('date') is-invalid @enderror"
                                        value="{{ old('date', $expense->date) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Notes --}}
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $expense->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Receipt Upload --}}
                                <div class="mb-3">
                                    <label class="form-label">Receipt</label>
                                   {!! getImage($expense->receipt_path, true) !!}
                                    <input type="file" name="receipt_path"
                                        class="form-control @error('receipt_path') is-invalid @enderror">
                                    @error('receipt_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Submit --}}
                                <button type="submit" class="btn btn-primary">Update Expense</button>
                                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->

        <!-- Media Preview Modal -->
        @include('admin.pages-partials.preview_modal')



    </div>
    <!-- Content wrapper -->
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        let serviceSelect = new TomSelect("#services", {
            plugins: ['remove_button'],
            placeholder: "Select services"
        });

        function updateTotal() {
            let total = 0;
            let selectedOptions = serviceSelect.getValue();

            selectedOptions.forEach(id => {
                let option = document.querySelector(`#services option[value="${id}"]`);
                if (option) {
                    total += parseFloat(option.getAttribute('data-price')) || 0;
                }
            });

            document.getElementById('services_total').value = total.toFixed(2);
        }

        // Run when changed
        serviceSelect.on('change', updateTotal);

        // Run on page load (in case of edit with pre-selected services)
        updateTotal();
    </script>
@endpush
