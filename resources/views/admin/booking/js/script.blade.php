<script>
    $(document).ready(function() {

        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('addons.datatable') }}',
            columns: [{
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'duration',
                    name: 'duration'
                },
                {
                    data: 'gender',
                    name: 'gender'
                },

                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]

        });
    });


    ///////// create page code
    $(document).ready(function() {
        let serviceIndex = 1;

        $('#addServiceBtn').on('click', function() {
            const $container = $('#service-container');

            // <option value="{{ $variant->id }}">{{ $variant->name }}</option>
            const $row = $(`
                            <div class="row mb-2 service-row">
                                <div class="col-md-6">
                                    <select name="services[${serviceIndex}][service_variant_id]" class="form-control" required>
                                         <option value="">Select Service</option>
                                        @foreach ($serviceVariants as $variant)
                                            <option value="{{ $variant->id }}" data-price="{{ $variant->price }}">{{ $variant->name }}</option>

                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number"  readonly  name="services[${serviceIndex}][price]" placeholder="Price" class="form-control" step="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <select name="services[${serviceIndex}][staff_id]" class="form-control">
                                        <option value="">Select Staff</option>
                                            @foreach ($staffMembers as $staff)
                                                <option value="{{ $staff->id }}"> {{ $staff->user?->name ?? 'Unknown' }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-service-btn">&times;</button>
                                </div>
                            </div>
                        `);

            $container.append($row);
            serviceIndex++;
        });

        // Handle dynamic row removal with minimum one row check
        $(document).on('click', '.remove-service-btn', function() {
            const $allRows = $('.service-row');

            // if ($allRows.length > 1) {
            //     $(this).closest('.service-row').remove();
            // } else {
            //     alert("At least one service is required.");
            // }



            $(this).closest('.service-row').remove();
            calculateTotals();

        });


        $(document).on('change', 'select[name*="[service_variant_id]"]', function() {
            const $row = $(this).closest('.service-row');
            const selectedOption = $(this).find('option:selected');
            const price = selectedOption.data('price') || 0;

            console.log('change', price);

            // Set price input value
            $row.find('input[name*="[price]"]').val(price);

            // Reset staff dropdown if no service selected
            if (!selectedOption.val()) {
                $row.find('select[name*="[staff_id]"]').val('');
            }

            calculateTotals(); // <-- Call this to ensure totals are recalculated on first change too
        });

    });

    function calculateTotals() {
        let serviceTotal = 0;
        let addonTotal = 0;

        // Sum service prices
        $('input[name^="services"][name$="[price]"]').each(function() {
            const val = parseFloat($(this).val());
            if (!isNaN(val)) {
                serviceTotal += val;
            }
        });

        // Sum addon prices
        $('input[name^="addons"][name$="[price]"]').each(function() {
            const checkbox = $(this).closest('.row').find('input[type="checkbox"]');
            if (checkbox.prop('checked')) {
                const val = parseFloat($(this).val());
                if (!isNaN(val)) {
                    addonTotal += val;
                }
            }
        });

        // Get offer info
        const offerOption = $('select[name="offer_id"] option:selected');
        const offerValue = parseFloat(offerOption.data('discount')) || 0;
        const offerType = offerOption.data('type');


        updateTotals(serviceTotal, addonTotal, offerType, offerValue)
    }

    function updateTotals(serviceTotal, addonsTotal, offerType, offerValue) {
        let subtotal = parseFloat(serviceTotal) + parseFloat(addonsTotal);
        let discount = 0;
        let finalTotal = subtotal;
        let validZero = false;
        let errorMsg = '';

        // Calculate discount based on offer type
        if (offerType === 'percentage') {
            discount = subtotal * (offerValue / 100);
        } else if (offerType === 'flat') {
            discount = offerValue;
        }

        finalTotal = subtotal - discount;

        if (finalTotal <= 0) {
            if ((offerType === 'percentage' && offerValue == 100 && subtotal > 0) ||
                (offerType === 'flat' && offerValue === subtotal && subtotal > 0)) {
                validZero = true;
            }

            if (!validZero) {
                // errorMsg = "Discount is too high. Total cannot be zero or negative.";
                errorMsg =
                    "⚠️ Discount too high! Total is $0.00. Please review the offer. Are you sure you want to proceed with a free booking?";

                alert(errorMsg); // Optional: show alert
                finalTotal = 0;
            }
        }

        // Update DOM
        $("#services-total").text(serviceTotal.toFixed(2));
        $("#addons-total").text(addonsTotal.toFixed(2));
        $("#subtotal").text(subtotal.toFixed(2));
        $("#offer-discount").text(discount.toFixed(2));
        $("#final-total").text(finalTotal.toFixed(2));


        // Handle messages
        if (errorMsg) {
            $("#error-message").removeClass('d-none').text(errorMsg);
            $("#discount-info").addClass('d-none');
        } else {
            $("#error-message").addClass('d-none');
            if (offerType && offerValue > 0) {
                let label = offerType === 'percentage' ? `(${offerValue}% Off)` : `($${offerValue.toFixed(2)} Off)`;
                $("#discount-info").removeClass('d-none').text(`Offer applied ${label}`);
            } else {
                $("#discount-info").addClass('d-none');
            }
        }
    }

    $(document).on('change',
        'input[type="checkbox"][name^="addons"], select[name="offer_id"]',
        calculateTotals);

    $(document).on('click', '#addServiceBtn', function() {
        setTimeout(calculateTotals, 200); // slight delay to allow DOM update
    });
</script>
