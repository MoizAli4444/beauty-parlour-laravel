<script>
    $(document).ready(function() {

        var table = $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('booking-reviews.datatable') }}",
                data: function(d) {
                    d.customer_id = $('select[name=customer_id]').val();
                    d.status = $('select[name=status]').val();
                    // d.rating = $('select[name=rating]').val();
                    d.rating = $('#filterRating').val();

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
                    data: 'customer',
                    name: 'customer'
                },
                {
                    data: 'booking',
                    name: 'booking'
                },
                {
                    data: 'rating',
                    name: 'rating',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'review',
                    name: 'review'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'moderator_name',
                    name: 'moderator_name'
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
            // url: "/bookings/" + bookingId + "/status",
            url: "{{ route('booking-reviews.changeStatus', ':id') }}".replace(':id', bookingId),
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
