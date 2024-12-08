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
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Block</a></li>
                                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#modal-add-block">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Block
                                </button>
                            </div>

                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <table id="block-table" class="table table-bordered table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">No</th>
                                                <th>Block</th>
                                                <th style="width: 30px;">Type</th>
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
                                                <th>Harga</th>
                                                <th>Luas Tanah</th>
                                                <th>Luas Bangunan</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="timeline">
                                    dasda
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">
                                    sad
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
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

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 items-center">
                <div class="col d-flex items-center ">
                    <div>

                    </div>
                    <h1 class="ml-3">{{ $result->kegiatan }}</h1>

                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 col-sm-3">
                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Pembelian Material</a>
                                        <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Tukang</a>
                                        <a class="nav-link" id="vert-tabs-worker-payment-tab" data-toggle="pill" href="#vert-tabs-worker-payment" role="tab" aria-controls="vert-tabs-worker-payment" aria-selected="false">Pembayaran Upah Tukang</a>
                                    </div>
                                </div>
                                <div class="col-8 col-sm-9">
                                    <div class="tab-content" id="vert-tabs-tabContent">
                                        <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                            <div class="d-flex justify-content-between">
                                                <h4>Pembelian Material</h4>
                                                <div class="">
                                                    @if($result->status == "process")
                                                    <a href="{{ route('transaction-materials.index', $result->id) }}" class="btn btn-success btn-sm">
                                                        <i
                                                            class="nav-icon fas fa-plus-circle"></i>&nbsp;
                                                        Tambah Pembelian Material
                                                    </a>

                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <table id="purchase-table" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5px;"> No</th>
                                                            <th>Vendor</th>
                                                            <th>Tanggal Transaksi</th>
                                                            <th>Bukti Transaksi</th>
                                                            <th>Detail</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($materialPurchases as $index => $item)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $item->vendor->nama_vendor }}</td>
                                                            <td>{{ $item->transaction_date }}</td>
                                                            <td>
                                                                @if($item->attachment != null || $item->attachment != '')
                                                                <a href="{{ $item->attachment }}" class="btn btn-sm btn-default" target="_blank"><i class="fas fa-eye mr-2"></i>Lihat</a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('transaction-materials.detailTransaction', $item->id) }}" class="btn btn-sm btn-default"><i class="fas fa-eye mr-2"></i>Detail Transaksi</a>
                                                            </td>
                                                            <td>Rp {{ number_format($item->total, 2, ',', '.') }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5">Total</th>
                                                            <th>Rp {{ number_format($total, 2, ',', '.') }}</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                            <div class="col-lg-6">
                                                @if($result->status == "process")
                                                <form action="{{ route('project.addDetail', $result->id) }}" method="POST"
                                                    id="form-add-worker">
                                                    @csrf
                                                    <div class="flex ">

                                                        <label>Tukang</label>
                                                        <select class="form-control" name="worker_id[]" multiple id="worker_id">
                                                        </select>
                                                        <div class="form-group mt-3">
                                                            <label>Tanggal Bergabung</label>
                                                            <input type="date" class="form-control" name="join_date" id="join_date" required>
                                                        </div>

                                                    </div>
                                                    <button type="button" id="btn-add-worker"
                                                        class="btn btn-success btn-sm"><i
                                                            class="nav-icon fas fa-plus-circle"></i>&nbsp;Tambah tukang
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                            <div class="mt-4">
                                                <h5>Tukang yang terlibat</h5>
                                                <table class="table table-bordered" id="worker-table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5px;">No</th>
                                                            <th>Nama Tukang</th>
                                                            <th>Tanggal Bergabung</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-worker-payment" role="tabpanel" aria-labelledby="vert-tabs-worker-payment-tab">
                                            <div class="mt-4">
                                                <div class="d-flex justify-content-between">
                                                    <h4>Pembayaran Upah Tukang</h4>
                                                    <div class="">
                                                        @if($result->status == "process")
                                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-payment">
                                                            <i
                                                                class="nav-icon fas fa-plus-circle"></i>&nbsp;
                                                            Tambah Pembayaran Baru
                                                        </button>

                                                        @endif
                                                    </div>
                                                </div>
                                                <table class="table table-bordered mt-3" id="worker-payment-table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5px;">No</th>
                                                            <th>Minggu Ke</th>
                                                            <th>Tanggal Pembayaran</th>
                                                            <th>Total</th>
                                                            <th>Bukti Pembayaran</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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