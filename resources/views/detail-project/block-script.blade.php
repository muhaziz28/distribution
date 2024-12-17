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
                data: 'customer',
                render: function(data, type, row) {
                    if (data != null) {
                        return data.name
                    }
                    return '<span class="badge badge-warning">Customer belum ditambahkan</span>'
                }
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
                    return `<div class="btn-group">
                            <a href="${data.detail_url}" class="btn  btn-default">
                                <i class="fas fa-eye mr-2"></i> Detail
                            </a>
                            <button class="btn  btn-warning edit-block" data-id="${data.id}">
                                <i class="fas fa-pen mr-2"></i>
                                Edit
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
                    $('#customer_id').val(null).trigger('change');
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

    $('#customer_id').select2({
        ajax: {
            url: "{{ route('customer.data') }}",
            dataType: 'json',
            data: function(params) {
                return {
                    search: params.term,
                };
            },
            processResults: function(data) {
                return {
                    results: data.data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name,
                        };
                    })
                };
            }
        }
    });

    $('#form-add-customer').on('submit', function(e) {
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
                $('#form-add-customer button[type="submit"]').attr('disabled', true);
                $('#form-add-customer button[type="submit"]').html('Loading...');
            },
            success: function(response) {
                if (response.success) {
                    $('#modal-add-customer').modal('hide');
                    $('#form-add-customer')[0].reset();
                    $('#customer_id').val(null).trigger('change');
                    toastr.success(response.message);
                    table.DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(response.message);
                }
                $('#form-add-customer button[type="submit"]').attr('disabled', false);
                $('#form-add-customer button[type="submit"]').html('Save');
            }
        })
    })

    $(document).on('click', '.edit-block', function(e) {
        e.preventDefault()
        var data = tableBlock.DataTable().row($(this).closest('tr')).data();

        $('#modal-add-block').modal('show')
        $('#modal-add-block').find('#title').text('Edit Block')
        $('#form-add-block').attr('action', '{{ route("block.update") }}')
        $('#form-add-block').append('<input type="hidden" name="_method" value="PUT">')
        $('#form-add-block').append('<input type="hidden" name="id" value="' + data.id + '">')
        $('#block').val(data.block)
        $('#type').val(data.type)
        $('#harga').val(data.harga)
        $('#luas_tanah').val(data.luas_tanah)
        $('#luas_bangunan').val(data.luas_bangunan)
        if (data.customer != null) {
            var newOption = new Option(data.customer.name, data.customer_id, true, true)
            $('#customer_id').append(newOption).trigger('change')
        } else {
            $('#customer_id').val(null).trigger('change');
        }

    })
</script>