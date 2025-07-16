<script>
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
</script>
