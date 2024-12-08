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
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('transaction-materials.index') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Pembelian Material
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="material-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Bahan</th>
                                        <th>Vendor</th>
                                        <th>Qty</th>
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
                    data: null,
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
    })
</script>
@endpush