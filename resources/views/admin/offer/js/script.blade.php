<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('offers.datatable') }}',
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
                    data: 'type',
                    name: 'type' 
                },
                {
                    data: 'value',
                    name: 'value'
                },

                {
                    data: 'offer_code',
                    name: 'offer_code' 
                },

                {
                    data: 'starts_at',
                    name: 'starts_at'
                },
                {
                    data: 'ends_at',
                    name: 'ends_at' 
                },


                {
                    data: 'status',
                    name: 'status'
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



    $('.select2-multiple').each(function() {
        $(this).select2({
            placeholder: $(this).data('placeholder'),
            width: '100%'
        });
    });
</script>
