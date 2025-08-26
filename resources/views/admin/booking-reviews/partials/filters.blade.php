<div class="card mb-4 border-0 shadow-lg rounded-3">
    <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center rounded-top border-bottom"
        style="background: linear-gradient(135deg, #4e73df, #224abe);">
        <h6 class="mb-0 fw-bold">
            <i class="bi bi-funnel me-2"></i> Booking Filters
        </h6>
        <button class="btn btn-sm btn-light text-primary fw-semibold shadow-sm" type="button" data-bs-toggle="collapse"
            data-bs-target="#filterCollapse" aria-expanded="true">
            <i class="bi bi-sliders me-1"></i> Toggle Filters
        </button>
    </div>


    <div class="collapse mt-2" id="filterCollapse">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <!-- Customer -->
                <div class="col-md-3">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->user?->name ?? 'Unknown' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        @foreach ($review_statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Rating -->
                <div class="col-md-2">
                    <label class="form-label">Rating</label>
                    {{-- <select name="rating" class="form-select"> --}}
                    <select id="filterRating" name="rating" class="form-select">
                        <option value="">All</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>




                <!-- Buttons -->
                <div class="col-md-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Apply
                    </button>
                    <button type="button" id="resetFilter" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-repeat"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
