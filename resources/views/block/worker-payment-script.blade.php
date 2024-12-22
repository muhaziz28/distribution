<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        function defineColumnsWorkerPayment() {
            return [{
                    data: 'DT_RowIndex',
                },
                {
                    data: 'week',
                    render: function(data, type, row) {
                        return `Minggu ke -${data}`
                    }
                },
                {
                    data: 'payment_date',
                },
                {
                    data: 'attachment',
                    render: function(data, type, row) {
                        if (data != null || data != undefined) {
                            return `<a href="${row.attachment_url}" target="_blank" class="btn btn-default btn-sm">
                            <i class="fa fa-link mr-2"></i>Lihat FIle</a>`
                        }
                        return ''
                    }
                },
                {
                    data: 'total',
                    render: function(data, type, row) {
                        return formatRupiah(data)
                    }
                },
                {
                    data: null,
                    render: function(data, row) {
                        return `<button class="btn btn-sm btn-danger delete-worker-payment" data-id="${data.id}">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>`
                    }
                }
            ];
        }
        // Material
        var tableWorker = $('#worker-table');
        var configWorker = {
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('worker-payment.data', $result->id) }}",
            },
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            footerCallback: function(row, data, start, end, display) {
                let total = data.reduce((sum, item) => sum + item.total, 0);
                let formattedTotal = formatRupiah(total)
                $('#total_keseluruhan_pembayaran_tukang').text(`Total Keseluruhan: ${formattedTotal}`);
            },
            columns: defineColumnsWorkerPayment()
        };

        initializeDataTable(tableWorker, configWorker);

        $('#is_block_activity').on('change', function() {
            let isBlockActivityChecked = $(this).is(':checked');
            if (isBlockActivityChecked) {
                $('#activity_name').val('').attr('disabled', true);
            } else {
                $('#activity_name').attr('disabled', false);
            }
        });


        $('#form-add-activity').on('submit', function(e) {
            e.preventDefault();

            let isValid = true
            let errorMessages = [];

            let isBlockActivityChecked = $('#is_block_activity').is(':checked');
            let activityName = $('#activity_name').val().trim();

            if (!isBlockActivityChecked && !activityName) {
                isValid = false;
                errorMessages.push(
                    'Harus mengisi "Nama Aktivitas" atau mencentang "Aktivitas pengerjaan block".'
                );
            }

            if (!isValid) {
                toastr.error('Validasi gagal:\n' + errorMessages.join('\n'));
                return;
            }

            $('#is_block_activity').is(':checked') ? 1 : 0
            var form = new FormData(this);
            form.append('is_block_activity', isBlockActivityChecked ? 1 : 0)
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#form-add-activity button[type="submit"]').attr('disabled', true);
                    $('#form-add-activity button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $('#modal-add-activity').modal('hide');
                        $('#form-add-activity')[0].reset();
                        toastr.success(response.message);
                        tableActivity.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-activity button[type="submit"]').attr('disabled', false);
                    $('#form-add-activity button[type="submit"]').html('Save');
                },
                error: function(xhr) {
                    console.error(xhr.responseText)
                    let response = xhr.responseJSON

                    if (response && response.errors) {
                        $.each(response.errors, function(field, messages) {
                            messages.forEach(function(message) {
                                toastr.error(message)
                            });
                        });
                    } else {
                        toastr.error('Terjadi kesalahan. Silakan coba lagi.')
                    }
                }
            });
        });

        $(document).on('click', '.delete-activity', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus pekerjaan ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("activity.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            tableActivity.DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(response.message)
                        }
                    }
                })
            }
        })

        $(document).on('click', '.edit-activity', function(e) {
            e.preventDefault()
            var data = tableActivity.DataTable().row($(this).closest('tr')).data();

            $('#modal-add-activity').modal('show');
            $('#modal-add-activity').find('#title').text('Edit Pekerjaan');
            $('#form-add-activity').attr('action', '{{ route("activity.update") }}');
            $('#form-add-activity').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-activity').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#activity_name').val(data.activity_name);
            $('#is_block_activity').prop('checked', data.is_block_activity == 1);

        })

        $('#modal-add-activity').on('hidden.bs.modal', function() {
            $('#modal-add-activity').find('#title').text('Tambah Pekerjaan');
            $('#form-add-activity input[name="_method"]').remove();
            $('#form-add-activity input[name="id"]').remove();
            $('#form-add-activity').attr('action', '{{ route("activity.store") }}');
            $('#form-add-activity')[0].reset();
        })

        $(document).on('click', '.delete-worker-payment', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus data pembayaran ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("worker-payment.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        tableWorker.DataTable().ajax.reload(null, false);
                    }
                })
            }
        })
    })
</script>