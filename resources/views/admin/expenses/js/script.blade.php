<script>
    $(document).ready(function() {
        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('expenses.datatable') }}', // route for expenses
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
                    data: 'expense_type',
                    name: 'expense_type'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'receipt',
                    name: 'receipt',
                    orderable: false,
                    searchable: false
                },
                // {
                //     data: 'status',
                //     name: 'status'
                // },
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
</script>
