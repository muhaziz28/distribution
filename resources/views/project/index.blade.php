@extends('layouts.app')

@section('title', 'Project')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project</h1>
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
                            <div class="card-tools">
                                @can('create-project')
                                <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                    data-target="#modal-add-project">
                                    <i class="nav-icon fas fa-plus-circle"></i>&nbsp;
                                    Tambah Kegiatan
                                </button>
                                @endcan
                            </div>
                            <table id="project-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style=" width: 10px;">No</th>
                                        <th style="width: 60px;">TA</th>
                                        <th>Kegiatan</th>
                                        <th>Pekerjaan</th>
                                        <th>Lokasi</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>TA</th>
                                        <th>Kegiatan</th>
                                        <th>Pekerjaan</th>
                                        <th>Lokasi</th>
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

@can('create-project')
@include('project.modal')
@endcan
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
                    data: null,
                    render: function(data) {
                        return `
                                <div class="btn-group">
                                    <a href="${data.detail_url}" class="btn btn-default">
                                        <i class="fas fa-eye mr-2"></i> Detail
                                    </a>
                                    ${data.can_update ? `
                                            <button class="btn btn-warning edit" data-id="${data.id}">
                                            <i class="fas fa-pen mr-2"></i> Edit
                                            </button>` : ''}
                                    ${data.can_delete ? `
                                            <button class="btn btn-danger delete" data-id="${data.id}">
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

        $('#form-add-project').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#form-add-project button[type="submit"]').attr('disabled', true);
                    $('#form-add-project button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-project').modal('hide');
                        $('#form-add-project')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-project button[type="submit"]').attr('disabled', false);
                    $('#form-add-project button[type="submit"]').html('Submit');
                }
            });
        });


        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Are you sure you want to delete this project?');

            if (result) {
                $.ajax({
                    url: "{{ route('project.destroy') }}",
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        table.DataTable().ajax.reload();
                    }
                })
            }
        })

        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            var data = table.DataTable().row($(this).closest('tr')).data();

            $('#modal-add-project').modal('show');
            $('#modal-add-project').find('#title').text('Edit Project');
            $('#form-add-project').attr('action', "{{ route('project.update') }}");
            $('#form-add-project').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-project').append('<input type="hidden" name="id" value="' + data.id + '">');

            $('#form-add-project input[name="tahun_anggaran"]').val(data.tahun_anggaran);
            $('#form-add-project input[name="kegiatan"]').val(data.kegiatan);
            $('#form-add-project input[name="pekerjaan"]').val(data.pekerjaan);
            $('#form-add-project input[name="lokasi"]').val(data.lokasi);
        });

        $('#modal-add-project').on('hidden.bs.modal', function() {
            $('#modal-add-project').find('#title').text('Add Project');
            $('#form-add-project input[name="_method"]').remove();
            $('#form-add-project input[name="id"]').remove();
            $('#form-add-project #is_active').closest('.form-group')
                .remove();
            $('#form-add-project').attr('action', "{{ route('project.store') }}");
            $('#form-add-project')[0].reset();
        });


        function ambilTahun(input) {
            const tanggal = new Date(input.value);
            if (input.value) {
                const tahun = tanggal.getFullYear();
                input.value = tahun + "-01-01";
                alert("Tahun yang dipilih: " + tahun);
            }
        }
    })
</script>
@endpush