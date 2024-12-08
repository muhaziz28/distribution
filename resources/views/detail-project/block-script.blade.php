<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function defineColumnsBlock() {
        return [{
                data: 'DT_RowIndex',
            },
            {
                data: 'block',
            },
            {
                data: 'type',
            },
            {
                data: 'harga',
            },
            {
                data: 'luas_tanah',
            },
            {
                data: 'luas_bangunan',
            },
            {
                data: null,
                render: function(data, type, row) {

                    return `<div class="flex items-center justify-end space-x-2">
                            
                            <button class="btn btn-sm btn-default text-danger delete" data-id="${data.id}">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        </div>`;
                }
            }
        ];
    }

    var tableBlock = $('#block-table');
    var configBlock = {
        processing: true,
        serverSide: true,
        ajax: "{{ route('block.data', $result->id) }}",
        paging: true,
        ordering: true,
        info: false,
        searching: true,
        lengthChange: true,
        lengthMenu: [10, 25, 50, 100],
        columns: defineColumnsBlock()
    };

    initializeDataTable(tableBlock, configBlock);

    $('#form-add-block').on('submit', function(e) {
        e.preventDefault();
        var form = new FormData(this)
        $.ajax({
            url: $(this).attr('action'),
            method: "POST",
            data: form,
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                $('#form-add-block button[type="submit"]').attr('disabled', true);
                $('#form-add-block button[type="submit"]').html('Loading...');
            },
            success: function(response) {
                if (response.success) {
                    $('#modal-add-block').modal('hide');
                    $('#form-add-block')[0].reset();
                    toastr.success(response.message);
                    tableBlock.DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(response.message);
                }
                $('#form-add-block button[type="submit"]').attr('disabled', false);
                $('#form-add-block button[type="submit"]').html('Save');
            }

        })
    })
</script>