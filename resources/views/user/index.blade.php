@extends('layouts.apps')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Datatable
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <button class="button text-white bg-theme-1 shadow-md mr-2">Add New Product</button>
        <div class="dropdown relative ml-auto sm:ml-0">
            <button class="dropdown-toggle button px-2 box text-gray-700">
                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
            </button>
            <div class="dropdown-box mt-10 absolute w-40 top-0 right-0 z-20">
                <div class="dropdown-box__content box p-2">
                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> <i data-feather="file-plus" class="w-4 h-4 mr-2"></i> New Category </a>
                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> <i data-feather="users" class="w-4 h-4 mr-2"></i> New Group </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="intro-y datatable-wrapper box p-5 mt-5">
    <table class="table table-report table-report--bordered display datatable w-full" id="user-table">
        <thead>
            <tr>
                <th class="border-b-2 whitespace-no-wrap">NO</th>
                <th class="border-b-2 whitespace-no-wrap">NAME</th>
                <th class="border-b-2 whitespace-no-wrap">EMAIL</th>
                <th class="border-b-2 whitespace-no-wrap">ROLE</th>
                <th class="border-b-2 whitespace-no-wrap">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


@can('create-users')
<div class="modal fade" id="modal-add-user">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.store') }}" method="POST" id="form-add-user">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Add New User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control role-select" name="role" id="role" style="width: 100%;">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" id="password" name="password" placeholder="Password">
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
                    data: 'name',
                },
                {
                    data: 'email',
                },
                {
                    data: 'roles',
                    render: function(data, type, row) {
                        if (data.length > 0) {

                            return data[0].name;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<div class="flex items-center justify-end space-x-2">
                        @can('update-users')
                            <button class="btn btn-sm btn-outline-primary edit" data-id="${data.id}">Edit</button>
                        @endcan
                        @can('delete-users')
                            <button class="btn btn-sm btn-outline-danger delete" data-id="${data.id}">Delete</button>
                        @endcan
                        </div>`;
                    }
                }
            ]
        }

        var table = $('#user-table');
        var config = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.data') }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],

            columns: defineColumns()
        };

        initializeDataTable(table, config);

        $('#form-add-user').on('submit', function(e) {
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
                    $('#form-add-user button[type="submit"]').attr('disabled', true);
                    $('#form-add-user button[type="submit"]').html('<iconify-icon class="text-xl spin-slow ltr:mr-2 rtl:ml-2 relative top-[1px]" icon="line-md:loading-twotone-loop"></iconify-icon><span>Loading</span>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-user').modal('hide');
                        $('#form-add-user')[0].reset();
                        toastr.success(response.message);
                        table.DataTable().ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                    $('#form-add-user button[type="submit"]').attr('disabled', false);
                    $('#form-add-user button[type="submit"]').html('Submit');
                }

            })
        })

        $(document).on('click', '.delete', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Are you sure you want to delete this user?');

            if (result) {
                $.ajax({
                    url: '{{ route("user.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        table.DataTable().ajax.reload();
                    }
                })
            }
        })

        $(document).on('click', '.edit', function(e) {
            e.preventDefault()
            var data = table.DataTable().row($(this).closest('tr')).data();

            $('#modal-add-user').modal('show');
            $('#modal-add-user').find('#title').text('Edit User');
            $('#form-add-user').attr('action', '{{ route("user.update") }}');
            $('#form-add-user').append('<input type="hidden" name="_method" value="PUT">');
            $('#form-add-user').append('<input type="hidden" name="id" value="' + data.id + '">');

            $('#form-add-user input[name="name"]').val(data.name);
            $('#form-add-user input[name="email"]').val(data.email);
            // disable input password
            $('#form-add-user input[name="password"]').attr('disabled', true);
            var role = new Option(data.roles[0].name, data.roles[0].id, true, true);
            $('#form-add-user .role-select').append(role).trigger('change');


        })

        $('#modal-add-user').on('hidden.bs.modal', function() {
            $('#modal-add-user').find('#title').text('Add User');
            $('#form-add-user input[name="_method"]').remove();
            $('#form-add-user input[name="id"]').remove();
            $('#form-add-user').attr('action', '{{ route("user.store") }}');
            $('#form-add-user')[0].reset();
            $('#form-add-user .role-select').val(null).trigger('change');
            $('#form-add-user input[name="password"]').attr('disabled', false);
        })

        $('.role-select').select2({
            placeholder: 'Select a role',
            ajax: {
                url: '{{ route("role.data") }}',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.data.map(function(role) {
                            return {
                                id: role.id,
                                text: role.name
                            }
                        })
                    }
                }
            }
        })
    })
</script>
@endpush