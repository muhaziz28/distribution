@extends('layouts.app')

@section('title', "Customer")

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Customer</h1>
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
                            @can('create-customer')
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-customer">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Customer
                                </button>
                            </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <table id="customer-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Nama</th>
                                        <th style="width: 100px;">Kontak</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kontak</th>
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

@can('create-customer')
@include('customer.modal')
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
                    data: 'name',
                },
                {
                    data: 'no_hp',
                },
                {
                    data: null,
                    render: function(data, type, row) {

                        return `<div class="flex items-center justify-end space-x-2">
                            @can('update-customer')
                            <button class="btn btn-sm btn-info edit" data-id="${data.id}">
                                <i class="fas fa-pen mr-2"></i>
                                Edit
                            </button>
                            @endcan
                            @can('delete-customer')
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

        var table = $('#customer-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('customer.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-customer').on('submit', function(e) {
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
                    $('#form-add-customer button[type="submit"]').attr('disabled', true);
                    $('#form-add-customer button[type="submit"]').html('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-customer').modal('hide');
                        $('#form-add-customer')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-customer button[type="submit"]').attr('disabled', false);
                    $('#form-add-customer button[type="submit"]').html('Save');
                }

            })
        })


        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus customer ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("customer.destroy") }}',
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

            $('#modal-add-customer').modal('show');
            $('#modal-add-customer').find('#title').text('Edit Customer');
            $('#form-add-customer').attr('action', '{{ route("customer.update") }}');
            $('#form-add-customer').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-customer').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#name').val(data.name);
            $('#no_hp').val(data.no_hp);
        })

        $('#modal-add-customer').on('hidden.bs.modal', function() {
            $('#modal-add-customer').find('#title').text('Tambah Customer');
            $('#form-add-customer input[name="_method"]').remove();
            $('#form-add-customer input[name="id"]').remove();
            $('#form-add-customer').attr('action', '{{ route("customer.store") }}');
            $('#form-add-customer')[0].reset();
        })
    })
</script>
@endpush