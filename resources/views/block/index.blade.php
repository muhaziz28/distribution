@extends('layouts.app')

@section('title', $result->block)

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 row">
                    <a href="{{ route('project.detail', $result->project_id) }}" class="btn btn-default  mr-3">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <h1 class="m-0">Block: {{ $result->block }}</h1>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div>
    </div>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#material"
                                        data-toggle="tab">Material</a></li>
                                <li class="nav-item"><a class="nav-link" href="#absensi" data-toggle="tab">Absensi</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#payment" data-toggle="tab">Payment</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">

                                {{-- Material --}}
                                <div class="active tab-pane" id="material">
                                    <div class="row ">
                                        <div class="col-4">
                                            <div class="form-group filter">
                                                <label for="data">Tanggal</label>
                                                <input type="text" class="form-control datepicker material-filter" id="date"
                                                    name="date" placeholder="Pilih tanggal">
                                            </div>
                                        </div>
                                    </div>

                                    <table id="material-table" class="table table-bordered table-striped"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">No</th>
                                                <th>Bahan</th>
                                                <th>Qty</th>
                                                <th>Retur</th>
                                                <th>Tanggal</th>
                                                <th>Harga Satuan</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Bahan</th>
                                                <th>Qty</th>
                                                <th>Retur</th>
                                                <th>Tanggal</th>
                                                <th>Harga Satuan</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                {{-- Material --}}

                                {{-- Absensi --}}
                                <div class="tab-pane" id="absensi">
                                    <button type="button"
                                        class="btn btn-success mb-3" data-toggle="modal"
                                        data-target="#modal-add-activity">
                                        <i class="nav-icon fas fa-plus-circle"></i>&nbsp;
                                        Tambah Pekerjaan
                                    </button>

                                    <table id="activity-table" class="table table-bordered table-striped"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">No</th>
                                                <th>Pekerjaan</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Pekerjaan</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                {{-- Absensi --}}

                                {{-- Payment --}}
                                <div class="tab-pane" id="payment">
                                    <div class="row">
                                        <div class="col"><button type="button" class="btn btn-success  mb-3 mr-3"
                                                data-toggle="modal" data-target="#modal-add-payment-history">
                                                <i class="nav-icon fas fa-plus-circle"></i>&nbsp;
                                                Tambah Pembayaran Baru
                                            </button>
                                            <a href="{{ route('payment.additionalItem', $result->id) }}"
                                                class="btn btn-info  mb-3">
                                                <i class="nav-icon fas fa-plus-circle"></i>&nbsp;
                                                Tambah Pembayaran Item Tambahan
                                            </a>
                                        </div>
                                    </div>
                                    <table id="payment-table" class="table table-bordered table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">No</th>
                                                <th>Tanggal Pembayaran</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Total</th>
                                                <th>Bukti Pembayaran</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Pembayaran</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Total</th>
                                                <th>Bukti Pembayaran</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <h4 id="total_keseluruhan"></h4>
                                </div>
                                {{-- Payment --}}

                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>

    </section>
</div>
@include('tukang.modal')
@include('block.retur-modal')
@include('block.payment-model')
@include('block.activity-modal')
@endsection

@push('scripts')
@include('block.payment-script')
@include('block.activity-script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        // Material
        function defineColumns() {
            return [{
                    data: 'DT_RowIndex',
                },
                {
                    data: 'material',
                    render: function(data, row) {
                        return data.bahan.nama_bahan
                    }
                },
                {
                    data: 'distributed_qty',
                    render: function(data, type, row) {
                        return `${data} ${row.material.bahan.satuan.satuan}`
                    }
                },
                {
                    data: 'returned_qty',
                    render: function(data, type, row) {
                        if (data != null) {
                            return `<span class="badge badge-warning">${data} ${row.material.bahan.satuan.satuan}</span>`
                        }
                        return ''
                    }
                },
                {
                    data: 'distribution_date',
                    render: function(data) {
                        const date = new Date(data)
                        return new Intl.DateTimeFormat('id-ID', {
                            dateStyle: 'long'
                        }).format(date);
                    }
                },
                {
                    data: 'material.material_purchase_item.harga_satuan',
                    render: function(data) {
                        return formatRupiah(data)
                    }
                },
                {
                    data: 'material.material_purchase_item',
                    render: function(data, type, row) {
                        var total = data.harga_satuan * row.distributed_qty
                        return formatRupiah(total)
                    }
                },
                {
                    data: null,
                    render: function(data, row) {
                        return `<button class = "btn  btn-warning edit" data-id="${data.id}"><i class="fas fa-pen mr-2"></i>Retur</button>&nbsp;`
                    }
                }
            ];
        }
        var table = $('#material-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('block-material.data', $result->id) }}",
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
            columns: defineColumns()
        };
        initializeDataTable(table, config);


        const exportAbsensi = $('.detail-absensi')
        exportAbsensi.hide()

        $(".absensi-filter").daterangepicker({
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'), 10),
            autoApply: true,
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            exportAbsensi.show();
            table4.DataTable().ajax.reload();
        })

        exportAbsensi.on('click', function() {
            const dateRange = $(".absensi-filter").val()
            const startDate = dateRange.split(' - ')[0]
            const endDate = dateRange.split(' - ')[1]

        });

        $(".material-filter").daterangepicker({
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'), 10),
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD')
            var endDate = picker.endDate.format('YYYY-MM-DD')

            table.DataTable().ajax.reload();
        });

        $('#form-return').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this)
            console.log(this.id)
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#form-return button[type="submit"]').attr('disabled', true);
                    $('#form-return button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-return').modal('hide');
                        $('#form-return')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-return button[type="submit"]').attr('disabled', false);
                    $('#form-return button[type="submit"]').html('Save');
                }
            });
        })

        // Edit
        $(document).on('click', '.edit', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();
            console.log(data)
            var url = '{{ route("return", ":id") }}'.replace(':id', data.id || 0);

            $('#form-return').attr('action', url);
            $('#modal-return').modal('show');
            $('#form-return').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#item').val(data.material.bahan.nama_bahan);
            $('#returned_qty').attr('max', data.distributed_qty);
            $('#max_qty').text('Max Qty: ' + data.distributed_qty);
            $('#returned_qty').val('');
        });
    })
</script>
@endpush