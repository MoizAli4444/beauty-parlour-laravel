<script>
    $(document).ready(function() {
        $('#dealsDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('deals.datatable') }}',
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
                    data: 'image_preview', // 👈 build this in controller
                    name: 'image_preview',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'validity', // 👈 you can build "start_date - end_date" in controller
                    name: 'validity'
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
</script>
