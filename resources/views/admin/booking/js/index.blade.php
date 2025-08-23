<script>
    $(document).ready(function() {

        var table = $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('bookings.datatable') }}",
                data: function(d) {
                    d.customer_id = $('select[name=customer_id]').val();
                    d.status = $('select[name=status]').val();
                    d.payment_status = $('select[name=payment_status]').val();
                    d.date_from = $('input[name=date_from]').val();
                    d.date_to = $('input[name=date_to]').val();
                }
            },
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
                    data: 'customer_user_name',
                    name: 'customer_user_name'
                },
                {
                    data: 'appointment_time',
                    name: 'appointment_time'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'payment_status_badge',
                    name: 'payment_status_badge'
                },
                {
                    data: 'payment_method',
                    name: 'payment_method'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]

        });

        // 🔹 Apply filters without reloading the page
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // 🔹 Reset filters
        $('#resetFilter').on('click', function() {
            $('#filterForm')[0].reset(); // Reset form inputs
            table.ajax.reload(); // Reload table with no filters
        });
    });

    $(document).on("click", ".change-status-btn", function() {
        let bookingId = $(this).data("id");
        let currentStatus = $(this).data("status");

        $("#booking_id").val(bookingId);
        $("#status_select").val(currentStatus);
        $("#statusModal").modal("show");
    });

    // change status modal script
    $("#statusForm").submit(function(e) {
        e.preventDefault();

        let bookingId = $("#booking_id").val();
        let status = $("#status_select").val();

        $.ajax({
            url: "/bookings/" + bookingId + "/status",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: status,
            },
            success: function(response) {
                location.reload(); // refresh table
            },
            error: function(xhr) {
                alert("Something went wrong!");
            },
        });
    });
</script>
