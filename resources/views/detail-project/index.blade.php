@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 items-center">
                <div class="col">
                    <h1 class="m-0">{{ $result->kegiatan }}</h1>
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
                                <div class="col-5 col-sm-3">
                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Pembelian Material</a>
                                        <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Profile</a>
                                    </div>
                                </div>
                                <div class="col-7 col-sm-9">
                                    <div class="tab-content" id="vert-tabs-tabContent">
                                        <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                            <div class="d-flex justify-content-between">
                                                <h4>Pembelian Material</h4>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#modal-add-project">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Tambah Pembelian Material
                                                </button>
                                            </div>
                                            <div class="mt-3">
                                                <table id="role-table" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5px;"> No</th>
                                                            <th>Vendor</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Vendor</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                            Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
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

@include('detail-project.modal-transaction')
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
                    class: 'table-td'
                },
                {
                    data: 'tahun_anggaran',
                },
                {
                    data: 'kegiatan',
                },
                {
                    data: 'pekerjaan',
                },
                {
                    data: 'lokasi',
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data == "pending") {
                            return `<span class="badge bg-warning">Pending</span`
                        } else if (data == "process") {
                            return `<span class="badge bg-info">Process</span`
                        } else {
                            return `<span class="badge bg-success">Finished</span`
                        }
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                    <div class="flex items-center justify-end space-x-2">
                        <a href="${data.detail_url}" class="btn btn-sm btn-default">
                            <i class="fas fa-eye mr-2"></i> Detail
                        </a>
                        ${data.can_update ? `
                        <button class="btn btn-sm btn-info edit" data-id="${data.id}">
                            <i class="fas fa-pen mr-2"></i> Edit
                        </button>` : ''}
                        ${data.can_delete ? `
                        <button class="btn btn-sm btn-danger delete" data-id="${data.id}">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>` : ''}
                    </div>
                `;
                    }
                }
            ]
        }

        var table = $('#project-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('project.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],

            columns: defineColumns()
        };

        initializeDataTable(table, config);

    });
</script>
@endpush