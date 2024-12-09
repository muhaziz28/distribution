@extends('layouts.app')

@section('title', $result->kegiatan )

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 row items-center">
                    <a href="{{ route('project.index') }}" class="btn btn-default btn-sm mr-3">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <h1>{{ $result->kegiatan }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#modal-add-block">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Block
                                </button>
                            </div>
                            <table id="block-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Block</th>
                                        <th style="width: 30px;">Type</th>
                                        <th>Customer</th>
                                        <th>Harga</th>
                                        <th>Luas Tanah</th>
                                        <th>Luas Bangunan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Block</th>
                                        <th>Type</th>
                                        <th>Customer</th>
                                        <th>Harga</th>
                                        <th>Luas Tanah</th>
                                        <th>Luas Bangunan</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@include('detail-project.block-modal')
@include('customer.modal')

@include('detail-project.worker-payment-modal')
@endsection


@push('scripts')
@include('detail-project.block-script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function defineColumns() {
        return [{
                data: 'DT_RowIndex',
            },
            {
                data: 'tukang.nama_tukang',
            },
            {
                data: 'join_date',
                render: function(data, type, row) {
                    const joinDate = new Date(data);
                    const currentDate = new Date();
                    const diffTime = Math.abs(currentDate - joinDate);

                    const diffYears = Math.floor(diffTime / (1000 * 60 * 60 * 24 * 365.25));
                    const diffMonths = Math.floor((diffTime % (1000 * 60 * 60 * 24 * 365.25)) / (1000 * 60 * 60 * 24 * 30.44));
                    const diffDays = Math.floor((diffTime % (1000 * 60 * 60 * 24 * 30.44)) / (1000 * 60 * 60 * 24));

                    let duration = '';
                    if (diffYears > 0) {
                        duration += `${diffYears} tahun `;
                    }
                    if (diffMonths > 0) {
                        duration += `${diffMonths} bulan `;
                    }
                    if (diffDays > 0) {
                        duration += `${diffDays} hari`;
                    }

                    return `${duration} (bergabung: ${joinDate.toLocaleDateString()})`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    if (data.project.status === 'process') {
                        return `<div class="flex items-center justify-end space-x-2">
                            
                            <button class="btn btn-sm btn-default text-danger delete" data-id="${data.id}">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        </div>`;
                    }

                    return ''


                }
            }
        ];
    }

    var table = $('#worker-table');
    var config = {
        processing: true,
        serverSide: true,
        ajax: "{{ route('project.workerAssignmentData', $result->id) }}",
        paging: true,
        ordering: true,
        info: false,
        searching: true,
        lengthChange: true,
        lengthMenu: [10, 25, 50, 100],
        columns: defineColumns()
    };

    initializeDataTable(table, config);

    $('#worker_id').select2({
        ajax: {
            url: "{{ route('tukang.data') }}",
            dataType: 'json',
            delay: 250,
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
                            text: item.nama_tukang,
                        };
                    })
                };
            }
        }
    });

    $('#btn-add-worker').click(function() {
        const url = $('#form-add-worker').attr('action');
        const formData = $('#form-add-worker').serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#form-add-worker')[0].reset();
                    $('#worker_id').val(null).trigger('change');
                    toastr.success(response.message);
                    table.DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(response.message);
            },
        })
    });

    $(document).on('click', '.delete', function() {
        var workerProjectId = $(this).data('id');

        var result = confirm('Apakah Anda yakin ingin menghapus tukang ini dari project?');

        if (result) {
            $.ajax({
                url: "{{ route('project.hapusDetail') }}",
                data: {
                    id: workerProjectId,
                },

                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }
    });

    function defineColumnsPayment() {
        return [{
                data: 'DT_RowIndex',
            },
            {
                data: 'week',
            },
            {
                data: 'transaction_date',
            },
            {
                data: 'total',
            },
            {
                data: 'attachment',
                render: function(data, type, row) {
                    if (row.attachment != null) {
                        // menambahkan asset()
                        let url = row.attachment
                        console.log(url)
                        return `<a href="${url}" class="btn btn-default" targe="_blank"><i class="fas fa-eye mr-2"></i>Lihat </a>`
                    }

                    return ''
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    if (data.project.status === 'process') {
                        return `<div class="flex items-center justify-end space-x-2">
                            <button class="btn btn-sm btn-default text-danger delete-payment" data-id="${data.id}">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        </div>`;
                    }

                    return ''


                }
            }
        ];
    }

    var tablePayment = $('#worker-payment-table');
    var configPayment = {
        processing: true,
        serverSide: true,
        ajax: "{{ route('worker-payment.data', $result->id) }}",
        paging: true,
        ordering: true,
        info: false,
        searching: true,
        lengthChange: true,
        lengthMenu: [10, 25, 50, 100],
        columns: defineColumnsPayment()
    };

    initializeDataTable(tablePayment, configPayment);

    $(document).on('click', '.delete-payment', function() {
        var id = $(this).data('id');

        var result = confirm('Apakah Anda yakin ingin menghapus pembayaran ini?');

        if (result) {
            $.ajax({
                url: "{{ route('worker-payment.destroy') }}",
                data: {
                    id: id,
                },
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        tablePayment.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }
    });

    $('#form-add-payment').on('submit', function(e) {
        e.preventDefault();
        var form = new FormData(this)
        for (let [key, value] of form.entries()) {
            console.log(key, value);
        }
        $.ajax({
            url: $(this).attr('action'),
            method: "POST",
            data: form,
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                $('#form-add-payment button[type="submit"]').attr('disabled', true);
                $('#form-add-payment button[type="submit"]').html('Loading...');
            },
            success: function(response) {
                if (response.success) {
                    $('#modal-add-payment').modal('hide');
                    $('#form-add-payment')[0].reset();
                    toastr.success(response.message);
                    tablePayment.DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(response.message);
                }
                $('#form-add-payment button[type="submit"]').attr('disabled', false);
                $('#form-add-payment button[type="submit"]').html('Save');
            }

        })
    })
</script>
@endpush