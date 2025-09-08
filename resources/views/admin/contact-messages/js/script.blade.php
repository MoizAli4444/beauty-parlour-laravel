<script>
    $(document).ready(function() {
        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('contact-messages.datatable') }}',
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
                    data: 'customer', // built in controller (name + email + phone)
                    name: 'customer',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'message', // trimmed preview in controller
                    name: 'message',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'priority', // badge in controller
                    name: 'priority'
                },
                {
                    data: 'status', // badge in controller
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
