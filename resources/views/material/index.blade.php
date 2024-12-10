@extends('layouts.app')

@section('title', 'Material')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Material</h1>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="material-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Bahan</th>
                                        <th>Vendor</th>
                                        <th>Qty</th>
                                        <th>Log</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Bahan</th>
                                        <th>Vendor</th>
                                        <th>Qty</th>
                                        <th>Log</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('material.distribution-modal')
@endsection


@push('scripts')
<script>
    $.ajaxSetup({
        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        function defineColumns() {
            return [{
                    data: 'DT_RowIndex',
                },
                {
                    data: 'bahan.nama_bahan',
                },
                {
                    data: 'vendor.nama_vendor',
                },
                {
                    data: 'qty',
                },
                {
                    data: 'previous_qty',
                    render: function(data, type, row) {
                        if (data != null) {
                            const result = row.new_qty > data
                            if (result) {
                                return `Stok awal: ${data} <span class="badge badge-success">+${row.new_qty - row.previous_qty}</span>`
                            } else {
                                return `Stok awal: ${data} <span class="badge badge-danger">${row.new_qty - row.previous_qty}</span>`
                            }
                        }

                        return ''

                    }
                },
                {
                    data: null,
                    render: function(data, row) {
                        return `<button class="btn btn-sm btn-info edit" data-id="${data.id}">
                                <i class="fas fa-pen mr-2"></i>
                                Distribusikan
                            </button>`
                    }
                }
            ];
        }

        var table = $('#material-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('material.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-distribution').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this)
            $.ajax({
                url: "{{ route('distribution.distribute') }}",
                method: "POST",
                data: form,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#form-distribution button[type="submit"]').attr('disabled', true);
                    $('#form-distribution button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $('#modal-distribution').modal('hide');
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#modal-distribution').modal('hide');
                    $('#form-distribution button[type="submit"]').attr('disabled', false);
                    $('#form-distribution button[type="submit"]').html('Save');
                }

            })
        })

        $(document).on('click', '.edit', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();
            console.log(data)

            $('#modal-distribution').modal('show');
            $('#form-distribution').append('<input type="hidden" name="material_id" value="' + data.id + '">');
            $('#bahan').val(data.bahan.nama_bahan);
            $('#distributed_qty').attr('max', data.qty);
            $('#max_qty').text('Max Qty: ' + data.qty);
            $('#distributed_qty').val('');
            $('#project_id').val(null).trigger('change');
            $('#block_id').val(null).trigger('change');
        })

        $('#distributed_qty').on('input', function() {
            var max = $(this).attr('max');
            var value = parseFloat($(this).val());

            if (value > max) {
                $(this).val(max);
            }
        });

        $('#modal-distribution').on('hidden.bs.modal', function() {
            console.log('reset')
            $('#modal-distribution').find('#title').text('Tambah Satuan Baru');
            $('#form-distribution input[name="_method"]').remove();
            $('#form-distribution input[name="material_id"]').remove();
            $('#form-distribution').attr('action', '{{ route("satuan.store") }}');
            $('#max_qty').text();
            $('#form-distribution')[0].reset();

            $('#distributed_qty').val('');
            $('#project_id').val(null).trigger('change');
            $('#block_id').val(null).trigger('change');
        })

        let project_id = null
        $('#project_id').select2({
            ajax: {
                url: "{{ route('project.data') }}",
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
                                text: item.kegiatan,
                            };
                        })
                    };
                }
            }
        }).on('select2:select', function(e) {
            project_id = e.params.data.id
            $('#block_id').val(null).trigger('change')
            $('#block_id').prop('disabled', false)
        })

        $('#block_id').select2({
            ajax: {
                url: function() {
                    return "{{ route('block.data', ':id') }}".replace(':id', project_id || 0);
                },
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
                                text: `${item.block} - ${item.customer ? item.customer.name : ''}`,
                            };
                        })
                    };
                }
            }
        });
        $('#block_id').prop('disabled', true);
    })
</script>
@endpush