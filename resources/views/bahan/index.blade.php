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
                            <table id="bahan-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bahan</th>
                                        <th width="30%">Qty</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Bahan</th>
                                        <th>Qty</th>
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
@include('bahan.modal-create-bahan')
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
                    data: 'qty'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (data.name != 'admin') {
                            return `<div class="flex items-center justify-end space-x-2">
                            @can('update-bahan')
                            <button class="btn btn-sm btn-outline-primary edit" data-id="${data.id}">Edit</button>
                            @endcan
                            @can('delete-bahan')
                            <button class="btn btn-sm btn-outline-danger delete" data-id="${data.id}">Delete</button>
                            @endcan
                        </div>`;
                        }

                        return '';

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
            var result = confirm('Are you sure you want to delete this role?');

            if (result) {
                $.ajax({
                    url: '{{ route("role.destroy") }}',
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

            $('#modal-add-bahan').modal('show');
            $('#modal-add-bahan').find('#title').text('Edit Role');
            $('#form-add-role').attr('action', '{{ route("role.update") }}');
            $('#form-add-role').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-role').append('<input type="hidden" name="id" value="' + data.id + '">');
            $('#role').val(data.name);
        })

        $('#modal-add-bahan').on('hidden.bs.modal', function() {
            $('#modal-add-bahan').find('#title').text('Add Role');
            $('#form-add-role input[name="_method"]').remove();
            $('#form-add-role input[name="id"]').remove();
            $('#form-add-role').attr('action', '{{ route("role.store") }}');
            $('#form-add-role')[0].reset();
        })

        var checkedPermissions = {};

        $(document).on('click', '.permission', function(e) {
            e.preventDefault();
            var data = table.DataTable().row($(this).closest('tr')).data();
            console.log(data);
            $('#modal-permission-role').modal('show');
            $('#modal-permission-role').find('input[name="role"]').val(data.name);
            // Memeriksa dan mencentang izin yang dimiliki oleh role pada tabel permission
            checkRolePermissions(data);
        });

        function checkRolePermissions(roleData) {
            var rolePermissions = roleData.permissions;
            var permissionTable = $('#permission-table').DataTable();

            permissionTable.rows().every(function() {
                var rowData = this.data();
                var permissionId = rowData.id;

                var isPermissionOwned = rolePermissions.some(function(permission) {
                    return permission.id === permissionId;
                });

                if (isPermissionOwned || checkedPermissions[permissionId]) {
                    $(this.node()).find('input[type="checkbox"]').prop('checked', true);
                    checkedPermissions[permissionId] = true;
                } else {
                    $(this.node()).find('input[type="checkbox"]').prop('checked', false);
                    delete checkedPermissions[permissionId];
                }
            });
        }

        function defineColumns2() {
            return [{
                    data: 'DT_RowIndex',
                },
                {
                    data: 'name',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        var isChecked = checkedPermissions[data.id] ? 'checked' : '';
                        return `<div class="flex items-center justify-center space-x-2">
                            <input type="checkbox" class="form-checkbox" name="permissions[]" value="${data.id}" ${isChecked}>
                        </div>`;
                    }
                }
            ]
        }

        var table2 = $('#permission-table');
        var config2 = {
            parent: 'modal-permission-role',
            ajax: "{{ route('permission.data') }}",
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumns2(),
        };

        initializeDataTable(table2, config2);

        $('#permission-table').on('change', 'input[type="checkbox"]', function() {
            var permissionId = $(this).val();
            if ($(this).prop('checked')) {
                checkedPermissions[permissionId] = true;
            } else {
                delete checkedPermissions[permissionId];
            }
        });

        $('#form-permission-role').on('submit', function(e) {
            e.preventDefault();
            var permissions = Object.keys(checkedPermissions);
            var form = new FormData(this);
            form.append('permissions', permissions);
            permissions.forEach(function(permission) {
                form.append('permissions[]', permission);
            });
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form-permission-role button[type="submit"]').attr('disabled', true);
                    $('#form-permission-role button[type="submit"]').html('<iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" icon="line-md:loading-twotone-loop"></iconify-icon><span>Loading</span>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-permission-role').modal('hide');
                        $('#form-permission-role')[0].reset();
                        table.DataTable().ajax.reload();
                        toastr.success(response.message);
                    } else {
                        console.log(response);
                        toastr.error(response.message);
                    }
                    $('#form-permission-role button[type="submit"]').attr('disabled', false);
                    $('#form-permission-role button[type="submit"]').html('Submit');
                }
            });
        });

        $('#modal-permission-role').on('hidden.bs.modal', function() {
            $('#form-permission-role input[name="role"]').val('');
            $('#permission-table input[type="checkbox"]').prop('checked', false);
            checkedPermissions = {};
        })
    })
</script>
@endpush