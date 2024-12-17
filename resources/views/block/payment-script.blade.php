<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        function defineColumnsPayment() {
            return [{
                    data: 'DT_RowIndex',
                },
                {
                    data: 'payment_date',
                },
                {
                    data: 'payment_type',
                    render: function(data, type, row) {
                        if (data === "dp") {
                            return `<span class="badge badge-success">DP</span>`
                        }

                        if (data === "installment") {
                            return `<span class="badge badge-info">Cicilan</span>`
                        }

                        return data
                    }
                },
                {
                    data: 'total',
                    render: function(data, type, row) {
                        if (row.is_down_payment == true) {
                            return `<span class="badge badge-success"><i class="fa fa-check text-white"></i> DP</span> ${formatRupiah(data)}`
                        }
                        return formatRupiah(data)
                    }
                },
                {
                    data: 'attachment',
                    render: function(data, type, row) {
                        if (data != null) {
                            console.log(row)
                            return `<a href="${data}" class="btn btn-default">
                            <i class="fa fa-link mr-2"></i>
                            Lihat
                            </a>`
                        }
                        return ''
                    }
                },
                {
                    data: null,
                    render: function(data, row) {
                        return `<button class="btn btn-sm btn-danger delete-payment" data-id="${data.id}">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>`
                    }
                }
            ];
        }
        // Material
        var tablePayment = $('#payment-table');
        var configPayment = {
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('payment.data', $result->id) }}",
                type: "GET",
                data: function(d) {
                    d.date = $('.datepicker').val();
                    d.vendor = $('#vendor').val();
                }
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
                $('#total_keseluruhan').text(`Total Keseluruhan: ${formattedTotal}`);
            },
            columns: defineColumnsPayment()
        };

        initializeDataTable(tablePayment, configPayment);

        $('#form-add-payment-history').on('submit', function(e) {
            e.preventDefault();

            let payment = {
                total: $('#total').val(),
                payment_date: $('#payment_date').val(),
                note: $('#note').val(),
                payment_type: $('#payment_type').val(),
            }
            console.log(payment)

            var formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('payment', JSON.stringify(payment));
            const hiddenInput = document.querySelector('.filepond--data input[type="hidden"]');

            if (hiddenInput && hiddenInput.value) {
                const filePath = hiddenInput.value;
                formData.append('file', filePath)
            } else {
                console.warn('Tidak ada file');
            }

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $('#modal-add-payment-history').modal('hide');
                        $('#form-add-payment-history')[0].reset();
                        toastr.success(response.message);
                        tablePayment.DataTable().ajax.reload(null, false);

                        clearFilePond('.filepond');

                        const hiddenInput = document.querySelector('.filepond--data input[type="hidden"]');
                        if (hiddenInput) {
                            hiddenInput.value = '';
                        }

                        $('#attachment').val('');
                    } else {
                        toastr.error(response.message);
                    }
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

        $(document).on('click', '.delete-payment', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus data pembayaran ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("payment.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        tablePayment.DataTable().ajax.reload(null, false);
                    }
                })
            }
        })

        // $('#modal-add-payment-history').on('hidden.bs.modal', function() {
        //     $('#form-add-payment-history')[0].reset();
        // })
    })
</script>