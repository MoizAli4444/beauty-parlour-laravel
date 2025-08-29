<script>
    // Handles status toggle with SweetAlert confirmation prompt
    // Sends AJAX PATCH request with CSRF token to update status
    // On success, shows alert and reloads DataTable; shows error if request fails

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

                            Swal.fire('Updated!', response.message, 'success');
                            // el.replaceWith(response.badge);
                            // $('#indexPageDataTable').DataTable().ajax.reload(null, false);

                            $('#indexPageDataTable').length ?
                                $('#indexPageDataTable').DataTable().ajax.reload(null,
                                    false) :
                                el.replaceWith(response.badge);

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

    // Handles featured toggle with SweetAlert confirmation prompt
    // Sends AJAX PATCH request with CSRF token to update featured status
    // On success, shows alert and reloads DataTable; shows error if request fails

    $(document).on('click', '.toggle-featured', function() {
        let url = $(this).data('route');
        let id = $(this).data('id');
        let el = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to toggle the featured status?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'PATCH', // or POST depending on your route
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire('Updated!', response.message, 'success');

                            // If DataTable exists, reload; else replace badge
                            $('#indexPageDataTable').length ?
                                $('#indexPageDataTable').DataTable().ajax.reload(null,
                                    false) :
                                el.replaceWith(response.badge);

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


    // Handle single record deletion with confirmation using SweetAlert
    // Sends AJAX DELETE request with CSRF token and reloads DataTable on success
    // Displays success or error alert based on response

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
                        // $('#indexPageDataTable').DataTable().ajax.reload(null, false);

                        // $('#indexPageDataTable').length ?
                        //     $('#indexPageDataTable').DataTable().ajax.reload(null, false) :
                        //     window.location.href = route;

                        if ($('#indexPageDataTable').length) {
                            $('#indexPageDataTable').DataTable().ajax.reload(null, false);
                        } else {
                            // Extract base from destroy route and redirect to index
                            let indexRoute = route.replace(/\/\d+$/, ''); // Remove ID
                            window.location.href = indexRoute;
                        }


                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });

    /**
     * Bulk Action Handler for Services Table
     *
     * Toggle all row checkboxes when the "Select All" checkbox is clicked
     * This script handles bulk actions (Delete, Activate, Deactivate) for selected services
     * in a DataTable. It uses SweetAlert for confirmation dialogs and sends AJAX POST requests
     * to perform the actions. The action type and request URL are passed dynamically through
     * button data attributes (`data-url`, `data-action`, `data-message`).
     *
     * - getSelectedIds(): Collects all checked row checkboxes and returns their values as an array.
     * - .btn-bulk-actions click handler: Triggers confirmation modal and sends the appropriate
     *   AJAX request based on the action (delete/status update).
     *
     * This approach makes bulk action buttons reusable and easy to manage.
     */

    $('#select-all').on('click', function() {
        $('.row-checkbox').prop('checked', this.checked);
    });

    function getSelectedIds() {
        let ids = [];
        $('.row-checkbox:checked').each(function() {
            ids.push($(this).val());
        });
        return ids;
    }

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
                        $('#indexPageDataTable').DataTable().ajax.reload();
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
