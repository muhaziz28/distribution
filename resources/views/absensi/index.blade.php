@extends("layouts.app")

@section('title', "Detail")

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="d-flex">
                    <!-- Tombol back -->
                    <button onclick="history.back()" class="btn btn-default mr-3">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>

                    <h1 class="m-0">Detail Pekerjaan</h1>
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
                                <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-add-worker">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Tambah Pekerja
                                </button>
                            </div>

                            <table id="worker-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Nama Pekerja</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pekerja</th>
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
@include('absensi.worker-modal')
@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        function defineColumnsWorker() {
            return [{
                    data: 'DT_RowIndex',
                },
                {
                    data: 'tukang.nama_tukang',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-danger delete-worker-group" data-id="${data.id}">
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete
                                </button>`;
                    }
                },
            ];
        }

        var tableWorker = $('#worker-table');
        var configWorker = {
            processing: true,
            serverSide: true,
            ajax: "{{ route('worker-group.data', $activity->id) }}",
            paging: true,
            ordering: true,
            info: false,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            columns: defineColumnsWorker()
        };

        initializeDataTable(tableWorker, configWorker);

        $('#worker_id').select2({
            ajax: {
                url: "{{ route('tukang.dataForWorker') }}",
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
                                text: item.nama_tukang,
                            };
                        })
                    };
                }
            }
        });

        $(".save-worker").on('click', function(e) {
            e.preventDefault();

            const form = $("#form-add-worker");
            const formData = form.serialize();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#worker_id').val(null).trigger('change');
                        $('#form-add-worker')[0].reset();
                        $('#modal-add-worker').modal('hide');
                        tableWorker.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
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
            });
        });

        $(".save-next").on('click', function(e) {
            e.preventDefault();

            const form = $("#form-add-worker");
            const formData = form.serialize();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#worker_id').val(null).trigger('change');
                        tableWorker.DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
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
            });
        });

        $(document).on('click', '.delete-worker-group', function() {
            var id = $(this).data('id')
            console.log(id);
            var result = confirm('Apakah anda ingin menghapus data ini?');

            if (result) {
                $.ajax({
                    url: '{{ route("worker-group.destroy") }}',
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            tableWorker.DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                })
            }
        })
    })
</script>
@endpush