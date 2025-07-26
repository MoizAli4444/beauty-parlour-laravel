<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('staff.datatable') }}',
            columns: [{
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data:'id',
                    name:'id'
                },
                {
                    data: 'name',
                    name: 'name' // From users table
                },
                {
                    data: 'email',
                    name: 'email' // From users table
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'staff_role',
                    name: 'staff_role' // staff_roles relation
                },
                {
                    data: 'shift_name',
                    name: 'shift_name' // shifts relation
                },
                {
                    data: 'joining_date',
                    name: 'joining_date'
                },
                // {
                //     data: 'leaving_date',
                //     name: 'leaving_date'
                // },
                {
                    data: 'salary',
                    name: 'salary'
                },
                // {
                //     data: 'payment_schedule',
                //     name: 'payment_schedule'
                // },
                // {
                //     data: 'payment_method',
                //     name: 'payment_method.name' // payment_methods relation
                // },
                // {
                //     data: 'bank_account_number',
                //     name: 'bank_account_number'
                // },
                // {
                //     data: 'working_days',
                //     name: 'working_days'
                // },
                // {
                //     data: 'date_of_birth',
                //     name: 'date_of_birth'
                // },
                // {
                //     data: 'emergency_contact',
                //     name: 'emergency_contact'
                // },
                {
                    data: 'is_verified',
                    name: 'is_verified'
                },
                {
                    data: 'is_head',
                    name: 'is_head'
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
