@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Satuan</h1>
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
                            <h3 class="card-title"></h3>
                            @can('create-satuan')
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-satuan">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Satuan Baru
                                </button>
                            </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <table id="satuan-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Satuan</th>
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

@can('create-satuan')
<div class="modal fade" id="modal-add-satuan">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('satuan.store') }}" method="POST" id="form-add-satuan">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Satuan Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="satuan">Nama Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Nama Satuan">
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
                },
                {
                    data: 'satuan',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (data.name != 'admin') {
                            return `<div class="flex items-center justify-end space-x-2">
                            @can('update-satuan')
                            <button class="btn btn-sm btn-outline-primary edit" data-id="${data.id}">Edit</button>
                            @endcan
                            @can('delete-satuan')
                            <button class="btn btn-sm btn-outline-danger delete" data-id="${data.id}">Delete</button>
                            @endcan
                        </div>`;
                        }

                        return '';

                    }
                }
            ];
        }

        var table = $('#satuan-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('satuan.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-satuan').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this)
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#form-add-satuan button[type="submit"]').attr('disabled', true);
                    $('#form-add-satuan button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-satuan').modal('hide');
                        $('#form-add-satuan')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-satuan button[type="submit"]').attr('disabled', false);
                    $('#form-add-satuan button[type="submit"]').html('Save');
                }

            })
        })


        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus satuan ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("satuan.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    }
                })
            }
        })

        $(document).on('click', '.edit', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();
            console.log(data)

            $('#modal-add-satuan').modal('show');
            $('#modal-add-satuan').find('#title').text('Edit Satuan');
            $('#form-add-satuan').attr('action', '{{ route("satuan.update") }}');
            $('#form-add-satuan').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-satuan').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#satuan').val(data.satuan);
        })

        $('#modal-add-satuan').on('hidden.bs.modal', function() {
            $('#modal-add-satuan').find('#title').text('Tambah Satuan Baru');
            $('#form-add-satuan input[name="_method"]').remove();
            $('#form-add-satuan input[name="id"]').remove();
            $('#form-add-satuan').attr('action', '{{ route("satuan.store") }}');
            $('#form-add-satuan')[0].reset();
        })
    })
</script>
@endpush