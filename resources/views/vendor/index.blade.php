@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Vendor</h1>
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
                            @can('create-vendor')
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-vendor">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Vendor Baru
                                </button>
                            </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <table id="vendor-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Nama Vendor</th>
                                        <th style="width: 30px;">Kontak</th>
                                        <th>Alamat</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Vendor</th>
                                        <th>Kontak</th>
                                        <th>Alamat</th>
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

@can('create-vendor')
@include('vendor.modal')
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
                    data: 'nama_vendor',
                },
                {
                    data: 'kontak',
                },
                {
                    data: 'alamat',
                },
                {
                    data: null,
                    render: function(data, type, row) {

                        return `<div class="flex items-center justify-end space-x-2">
                            @can('update-vendor')
                            <button class="btn btn-sm btn-info edit" data-id="${data.id}">
                                <i class="fas fa-pen mr-2"></i>
                                Edit
                            </button>
                            @endcan
                            @can('delete-vendor')
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

        var table = $('#vendor-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('vendor.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-vendor').on('submit', function(e) {
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
                    $('#form-add-vendor button[type="submit"]').attr('disabled', true);
                    $('#form-add-vendor button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-vendor').modal('hide');
                        $('#form-add-vendor')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-vendor button[type="submit"]').attr('disabled', false);
                    $('#form-add-vendor button[type="submit"]').html('Save');
                }

            })
        })


        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus vendor ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("vendor.destroy") }}',
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

            $('#modal-add-vendor').modal('show');
            $('#modal-add-vendor').find('#title').text('Edit Vendor');
            $('#form-add-vendor').attr('action', '{{ route("vendor.update") }}');
            $('#form-add-vendor').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-vendor').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#nama_vendor').val(data.nama_vendor);
            $('#kontak').val(data.kontak);
            $('#alamat').val(data.alamat);
        })

        $('#modal-add-vendor').on('hidden.bs.modal', function() {
            $('#modal-add-vendor').find('#title').text('Tambah Vendor Baru');
            $('#form-add-vendor input[name="_method"]').remove();
            $('#form-add-vendor input[name="id"]').remove();
            $('#form-add-vendor').attr('action', '{{ route("vendor.store") }}');
            $('#form-add-vendor')[0].reset();
        })
    })
</script>
@endpush