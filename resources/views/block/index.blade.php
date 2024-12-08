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
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Block</a></li>
                                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">

                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#modal-add-block">
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