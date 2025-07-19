<script>
    $(document).ready(function() {
        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('service-variants.datatable') }}',
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
                    data: 'service',
                    name: 'service' 
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
