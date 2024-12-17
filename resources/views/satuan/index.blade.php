@extends('layouts.app')

@section('title', "Satuan")

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
                        <div class="card-body">
                            @can('create-satuan')
                            <div class="card-tools">
                                <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                    data-target="#modal-add-satuan">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Tambah Satuan
                                </button>
                            </div>
                            @endcan
                            <table id="satuan-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
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
@include('satuan.modal')
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
                        return `<div class="btn-group">
                            @can('update-satuan')
                            <button class="btn btn-warning edit" data-id="${data.id}">
                                <i class="fas fa-pen mr-2"></i>
                                Edit
                            </button>
                            @endcan
                            @can('delete-satuan')
                            <button class="btn btn-danger delete" data-id="${data.id}">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                            @endcan
                        </div>`;
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
                    console.log(response)
                    if (response.success) {
                        $('#form-add-satuan')[0].reset();
                        $('#modal-add-satuan').modal('hide');
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-satuan button[type="submit"]').attr('disabled', false);
                    $('#form-add-satuan button[type="submit"]').html('Save');
                },
                error: function(xhr) {
                    console.error(xhr.responseText)
                    let response = xhr.responseJSON

                    if (response && response.errors) {
                        $.each(response.errors, function(field, messages) {
                            messages.forEach(function(message) {
                                toastr.error(message)
                            });
                        });
                    } else {
                        toastr.error('Terjadi kesalahan. Silakan coba lagi.')
                    }
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
            $('#modal-add-satuan').find('#title').text('Tambah Satuan');
            $('#form-add-satuan input[name="_method"]').remove();
            $('#form-add-satuan input[name="id"]').remove();
            $('#form-add-satuan').attr('action', '{{ route("satuan.store") }}');
            $('#form-add-satuan')[0].reset();
        })
    })
</script>
@endpush