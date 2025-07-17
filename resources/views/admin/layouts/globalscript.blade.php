<script>
    // change status from datatable
    $(document).on('click', '.toggle-status', function() {
        let url = $(this).data('route');
        let id = $(this).data('id');
        let el = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to toggle the status?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {

                            // el.replaceWith(response.badge);
                            Swal.fire('Updated!', response.message, 'success');
                            $('#servicesTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Request failed.', 'error');
                    }
                });
            }
        });
    });

    // delete record form datatable
    $(document).on('click', '.delete-record', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let route = $(this).data('route');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message ||
                            'Record deleted successfully.', 'success');
                        // Optionally reload DataTable
                        $('#servicesTable').DataTable().ajax.reload(null, false);

                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });


    // Select all functionality datatable checkbox
    $('#select-all').on('click', function() {
        $('.row-checkbox').prop('checked', this.checked);
    });



    // =====================

    function getSelectedIds() {
        let ids = [];
        $('.row-checkbox:checked').each(function() {
            ids.push($(this).val());
        });
        return ids;
    }

    // $('#bulkDelete').on('click', function() {
    //     let ids = getSelectedIds();
    //     if (ids.length === 0) {
    //         return Swal.fire('No services selected.', '', 'warning');
    //     }

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "Selected services will be deleted!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#d33',
    //         cancelButtonColor: '#3085d6',
    //         confirmButtonText: 'Yes, delete them!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: '{{ route('services.bulkDelete') }}',
    //                 method: 'POST',
    //                 data: {
    //                     ids: ids,
    //                     _token: '{{ csrf_token() }}'
    //                 },
    //                 success: function(response) {
    //                     $('#servicesTable').DataTable().ajax.reload();
    //                     Swal.fire('Deleted!', 'Services have been deleted.', 'success');
    //                 }
    //             });
    //         }
    //     });
    // });

    $('.btn-bulk-actions').on('click', function() {
        let ids = getSelectedIds();
        if (ids.length === 0) {
            return Swal.fire('No services selected.', '', 'warning');
        }

        let url = $(this).data('url');
        let action = $(this).data('action');
        let message = $(this).data('message') || 'Are you sure?';

        Swal.fire({
            title: 'Confirm',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let postData = {
                    ids: ids,
                    _token: '{{ csrf_token() }}'
                };

                // Only add status if it's not delete
                if (action !== 'delete') {
                    postData.status = action;
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: postData,
                    success: function(response) {
                        $('#servicesTable').DataTable().ajax.reload();
                        Swal.fire('Success', 'Action completed successfully.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });


   
</script>
