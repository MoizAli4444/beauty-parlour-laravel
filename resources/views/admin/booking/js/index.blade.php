<script>
    $(document).ready(function() {

        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('bookings.datatable') }}',
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
    });
</script>
