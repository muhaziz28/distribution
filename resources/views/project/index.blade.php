@extends('layouts.app')

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
                        <div class="card-header">
                            <h3 class="card-title">Project</h3>
                            <div class="card-tools">
                                @can('create-project')
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-add-project">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add New Project
                                </button>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="project-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style=" width: 10px;">No</th>
                                        <th style="width: 60px;">TA</th>
                                        <th>Kegiatan</th>
                                        <th>Pekerjaan</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
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
                                        <th>Status</th>
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
<div class="modal fade" id="modal-add-project">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('project.store') }}" method="POST" id="form-add-project">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Add New Project</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun_anggaran">Tahun Anggaran</label>
                        <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran"
                            placeholder="Tahun Anggaran.." min="1900" max="2100">
                    </div>
                    <div class="form-group">
                        <label for="kegiatan">Kegiatan</label>
                        <input type="text" class="form-control" id="kegiatan" name="kegiatan"
                            placeholder="Kegiatan...">
                    </div>
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                            placeholder="Pekerjaan...">
                    </div>
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                            placeholder="Lokasi...">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="process">Process</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
                    render: function(data, type, row) {
                        return `<div class="flex items-center justify-end space-x-2">
                                @can('update-project')
                                    <button class="btn btn-sm btn-info edit" data-id="${data.id}">
                                    <i class="fas fa-pen mr-2"></i>
                                    Edit</button>
                                @endcan
                                @can('delete-project')
                                    <button class="btn btn-sm btn-danger delete" data-id="${data.id}"><i class="fas fa-trash mr-2"></i> Delete</button>
                                @endcan
                                </div>`;
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
            $('#form-add-project select[name="status"]').val(data.status);
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
            // Ambil nilai input date
            const tanggal = new Date(input.value);
            if (input.value) {
                // Ambil hanya tahun
                const tahun = tanggal.getFullYear();
                // Set kembali hanya tahun
                input.value = tahun + "-01-01"; // Default ke tanggal 1 Januari tahun itu
                alert("Tahun yang dipilih: " + tahun); // Optional
            }
        }
    })
</script>
@endpush