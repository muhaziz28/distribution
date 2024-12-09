@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bahan</h1>
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
                            @can('create-bahan')
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-bahan">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Bahan Baru
                                </button>
                            </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <table id="bahan-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Bahan</th>
                                        <th>Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Bahan</th>
                                        <th>Satuan</th>
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

@can('create-bahan')
@include('bahan.modal')
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
                    data: 'nama_bahan',
                },
                {
                    data: 'satuan',
                    render: function(data, type, row) {
                        if (data.deleted_at != null) {
                            return data.satuan + ` <span class="badge bg-danger"><div class="fa fa-times-circle"></div> Satuan sudah dihapus</span>  <button class="btn btn-xs btn-info restore-satuan"> Ketuk untuk menambahkan kembali</button>`
                        }

                        return data.satuan
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<div class="flex items-center justify-end space-x-2">
                            @can('update-bahan')
                            <button class="btn btn-sm btn-info edit" data-id="${data.id}">
                                <i class="fas fa-pen mr-2"></i>
                                Edit
                            </button>
                            @endcan
                            @can('delete-bahan')
                            <button class="btn btn-sm btn-danger delete" data-id="${data.id}">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                            @endcan
                        </div>`;


                    }
                }
            ];
        }

        var table = $('#bahan-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('bahan.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-bahan').on('submit', function(e) {
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
                    $('#form-add-bahan button[type="submit"]').attr('disabled', true);
                    $('#form-add-bahan button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-bahan').modal('hide');
                        $('#form-add-bahan')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                        $('.satuan_id').val(null).trigger('change');
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-bahan button[type="submit"]').attr('disabled', false);
                    $('#form-add-bahan button[type="submit"]').html('Save');
                }

            })
        })


        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus bahan ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("bahan.destroy") }}',
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
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();
            console.log(data)
            $('#modal-add-bahan').modal('show');
            $('#modal-add-bahan').find('#title').text('Edit Bahan');
            $('#form-add-bahan').attr('action', '{{ route("bahan.update") }}');
            $('#form-add-bahan').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-bahan').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#nama_bahan').val(data.nama_bahan);
            var newOption = new Option(data.satuan.satuan, data.satuan_id, true, true);


            $('#satuan_id').append(newOption).trigger('change');

        })

        $('#modal-add-bahan').on('hidden.bs.modal', function() {
            $('#modal-add-bahan').find('#title').text('Tambah Bahan Baru');
            $('#form-add-bahan input[name="_method"]').remove();
            $('#form-add-bahan input[name="id"]').remove();
            $('#form-add-bahan').attr('action', '{{ route("bahan.store") }}');
            $('#form-add-bahan')[0].reset();

            $('#satuan_id').val(null).trigger('change');
        })

        $('#satuan_id').select2({
            ajax: {
                url: "{{ route('satuan.data') }}",
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.satuan,
                            };
                        })
                    };
                }
            }
        });

        $(document).on('click', '.restore-satuan', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();
            console.log(data.satuan);

            if (!confirm('Apakah Anda yakin ingin mengembalikan data ini?')) {
                return;
            }

            var $this = $(this);
            $.ajax({
                url: "{{ route('satuan.restore') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    satuan: data.satuan.satuan
                },
                dataType: 'json',
                beforeSend: function() {
                    $this.attr('disabled', true).text('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Terjadi kesalahan saat memproses permintaan.');
                },
                complete: function() {
                    $this.attr('disabled', false).text('Restore');
                }
            });

        })

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
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#modal-add-satuan').modal('hide');
                    $('#form-add-satuan button[type="submit"]').attr('disabled', false);
                    $('#form-add-satuan button[type="submit"]').html('Save');
                }

            })
        })
    })
</script>
@endpush