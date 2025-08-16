<script>
    $(document).ready(function() {

        // $('#indexPageDataTable').DataTable({
        var table = $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            // ajax: '{{ route('bookings.datatable') }}',
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
                // {
                //     data: 'created_at',
                //     name: 'created_at'
                // },
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
</script>
