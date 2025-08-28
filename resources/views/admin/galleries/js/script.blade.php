
<script>
    $(document).ready(function() {
        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('galleries.datatable') }}',
            columns: [
                {
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
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'file_path',
                    name: 'file_path',
                    render: function(data, type, row) {
                        return `<img src="/storage/${data}" alt="preview" width="50" height="50">`;
                    }
                },
                {
                    data: 'media_type',
                    name: 'media_type'
                },
                {
                    data: 'featured',
                    name: 'featured',
                    render: function(data) {
                        return data == 1
                            ? '<span class="badge bg-success">Yes</span>'
                            : '<span class="badge bg-secondary">No</span>';
                    }
                },
                {
                    data: 'alt_text',
                    name: 'alt_text'
                },
                {
                    data: 'file_size',
                    name: 'file_size',
                    render: function(data) {
                        if (!data) return 'N/A';
                        let sizeKB = (data / 1024).toFixed(2);
                        return sizeKB + ' KB';
                    }
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
