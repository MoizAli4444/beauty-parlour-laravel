<script>
    $(document).ready(function() {
        $('#indexPageDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('galleries.datatable') }}',
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
                    data: 'title',
                    name: 'title'
                },
                //     {
                //         data: 'file_path',
                //         name: 'file_path',
                //         render: function(data, type, row) {
                //             if (row.media_type === 'image') {
                //                 return `
                //     <img src="/storage/${data}" 
                //          alt="preview" width="50" height="50" style="object-fit:cover; cursor:pointer;" 
                //          class="media-preview" 
                //          data-type="image" 
                //          data-src="/storage/${data}">
                // `;
                //             } else if (row.media_type === 'video') {
                //                 return `
                //     <video width="80" height="50" style="cursor:pointer;" class="media-preview" 
                //            data-type="video" 
                //            data-src="/storage/${data}">
                //         <source src="/storage/${data}" type="video/mp4">
                //     </video>
                // `;
                //             } else {
                //                 return `<a href="/storage/${data}" target="_blank">Download</a>`;
                //             }
                //         }
                //     },

                {
                    data: 'media_preview', // 👈 from your addColumn() in Controller
                    name: 'media_preview',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'media_type',
                    name: 'media_type'
                },
                {
                    data: 'featured',
                    name: 'featured',
                    // render: function(data) {
                    //     return data == 1 ?
                    //         '<span class="badge bg-success">Yes</span>' :
                    //         '<span class="badge bg-secondary">No</span>';
                    // }
                },
                {
                    data: 'alt_text',
                    name: 'alt_text'
                },
                {
                    data: 'file_size',
                    name: 'file_size',
                    // render: function(data) {
                    //     if (!data) return 'N/A';
                    //     let sizeKB = (data / 1024).toFixed(2);
                    //     return sizeKB + ' KB';
                    // }
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



        $(document).on('click', '.js-media-preview', function() {
            let type = $(this).data('type');
            let src = $(this).data('url'); // not data('src')
            let html = '';

            if (type === 'image') {
                html = `<img src="${src}" class="img-fluid rounded" alt="Preview">`;
            } else if (type === 'video') {
                html = `
            <video controls autoplay class="w-100 rounded">
                <source src="${src}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        `;
            }

            $('#mediaContainer').html(html);
            $('#mediaModal').modal('show');
        });



    });
</script>
