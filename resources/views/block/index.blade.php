@extends('layouts.app')

@section('title', $result->block)

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 row">
                    <a href="{{ route('project.detail', $result->project_id) }}" class="btn btn-default btn-sm mr-3">
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
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Material</a></li>
                                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">

                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <table id="material-table" class="table table-bordered table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">No</th>
                                                <th>Bahan</th>
                                                <th>Qty</th>
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
                                                <th>Tanggal</th>
                                                <th>Harga Satuan</th>
                                                <th>Total</th>
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
</div>
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
                        return `<button class = "btn btn-sm btn-warning edit" data-id="${data.id}"><i class="fas fa-pen mr-2"></i>Return</button>`
                    }
                }
            ];
        }

        var table = $('#material-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('block-material.data', $result->id) }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns()
        };

        initializeDataTable(table, config);

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    })
</script>
@endpush