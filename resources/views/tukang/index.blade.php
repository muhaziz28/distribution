@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tukang</h1>
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
                            <h3 class="card-title">Tukang</h3>
                            <div class="card-tools">
                                @can('create-tukang')
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                    data-target="#modal-add-tukang">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Tukang
                                </button>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="tukang-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Nama tukang</th>
                                        <th>No Hp</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama tukang</th>
                                        <th>No Hp</th>
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

@can('create-tukang')
@include('tukang.modal')
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
                    data: 'nama_tukang',
                },
                {
                    data: 'no_hp',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<div class="btn-group">
                        @can('update-tukang')
                            <button class="btn btn-warning edit" data-id="${data.id}">
                            <i class="fas fa-pen mr-2"></i>
                            Edit</button>
                        @endcan
                        @can('delete-tukang')
                            <button class="btn btn-danger delete" data-id="${data.id}"><i class="fas fa-trash mr-2"></i> Delete</button>
                        @endcan
                        </div>`;
                    }
                }
            ]
        }

        var table = $('#tukang-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('tukang.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],

            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-tukang').on('submit', function(e) {
            e.preventDefault();
            var noHp = $('#no_hp').val();

            if (noHp != "") {
                if (noHp.length > 14 || !/^\d+$/.test(noHp)) {
                    toastr.error('Nomor HP tidak boleh lebih dari 14 digit.');
                    return;
                }
            }

            var form = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $('#form-add-tukang button[type="submit"]').attr('disabled', true);
                    $('#form-add-tukang button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-tukang').modal('hide');
                        $('#form-add-tukang')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-tukang button[type="submit"]').attr('disabled', false);
                    $('#form-add-tukang button[type="submit"]').html('Submit');
                }
            });
        });


        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Are you sure you want to delete this tukang?');

            if (result) {
                $.ajax({
                    url: "{{ route('tukang.destroy') }}",
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

            $('#modal-add-tukang').modal('show');
            $('#modal-add-tukang').find('#title').text('Edit Tukang');
            $('#form-add-tukang').attr('action', "{{ route('tukang.update') }}");
            $('#form-add-tukang').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-tukang').append('<input type="hidden" name="id" value="' + data.id + '">');

            $('#form-add-tukang input[name="nama_tukang"]').val(data.nama_tukang);
            $('#form-add-tukang input[name="no_hp"]').val(data.no_hp);
        });

        $('#modal-add-tukang').on('hidden.bs.modal', function() {
            $('#modal-add-tukang').find('#title').text('Add Tukang');
            $('#form-add-tukang input[name="_method"]').remove();
            $('#form-add-tukang input[name="id"]').remove()
            $('#form-add-tukang').attr('action', "{{ route('tukang.store') }}");
            $('#form-add-tukang')[0].reset();
        });
    })
</script>
@endpush