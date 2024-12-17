@extends('layouts.app')

@section('title', $result->kegiatan)
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
                                <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                    data-target="#modal-add-block">
                                    <i class="fas fa-plus-circle mr-2"></i>
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
@endsection


@push('scripts')
@include('detail-project.block-script')
@endpush