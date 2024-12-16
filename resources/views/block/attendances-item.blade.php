@extends('layouts.app')

@section('title', 'Absensi Tukang')

@section('content')
    <style>
        .active-row {
            background-color: #e3f4ec;
            /* Hijau terang */
            font-weight: bold;
        }
    </style>
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 row">

                        <a href="{{ route('block.detail', request()->route('blockID')) }}"
                            class="btn btn-default btn-sm mr-3">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <h1 class="m-0"></h1>
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
                                <h4 class="m-0"> Aktivitas</h4>
                            </div>

                            <div class="card-body">
                                <form id="activity-form" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" id="id" value="{{ $blockID }}">
                                    <div class="mb-3 row">
                                        <div class="col-12">
                                            <label for="activity_name" class="form-label">Aktivitas Pengerjaan Block</label>
                                            <input type="checkbox" name="is_block_activity" id="is_block_activity"
                                                class="worker-checkbox">
                                        </div>
                                        <div class="col-12">

                                            <label for="activity_name" class="form-label">Aktivitas</label>
                                            <input class="form-control" id="activity_name" name="activity_name"
                                                placeholder="Nama Activity" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>
                                        Tambah</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-0">Tukang</h4>
                            </div>
                            <div class="container my-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pilih</th>
                                            <th>Nama Tukang</th>
                                            <th>Durasi Kerja</th>
                                            <th>Upah</th>
                                            <th>Pinjaman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($workerAttendances as $index => $attendance)
                                            <tr id="{{ $attendance->id }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <input type="checkbox" name="worker_ids[]" id="worker_ids[]"
                                                        value="{{ $attendance->id }}" class="worker-checkbox">
                                                </td>
                                                <td>{{ $attendance->tukang->nama_tukang ?? '-' }}</td>
                                                {{-- <td>{{ $attendance->durasi_kerja ?? 0 }} Jam</td> --}}
                                                <form action="" id="attendance-form">
                                                    @csrf
                                                    <input type="hidden" name="activity_id" id="activity_id">
                                                    <td>
                                                        <select name="durasi_kerja" id="durasi_kerja" class="form-control">
                                                            <option value="">-- Durasi Kerja --</option>
                                                            <option value="1">1 Hari</option>
                                                            <option value="0.5">0.5 Hari</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="upah" id="upah"
                                                            class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="pinjaman" id="pinjaman"
                                                            class="form-control">
                                                    </td>
                                                </form>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary float-end mt-3 additional-item-process my-3 btn-attandace">Proses
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#is_block_activity').on("change", function(e) {
            if ($(this).is(':checked')) {
                $('#activity_name').prop('disabled', true);
                $('#activity_name').val('');
            } else {
                $('#activity_name').prop('disabled', false);
                $('#activity_name').val('');
            }
        });

        $('.worker-checkbox').on('change', function() {
            let row = $(this).closest('tr');

            if ($(this).is(':checked')) {
                row.addClass('active-row');
                row.find('input, select').not('.worker-checkbox').prop('disabled', false);
            } else {
                row.removeClass('active-row');
                row.find('input, select').not('.worker-checkbox').prop('disabled', true).val('')

            }
        });

        $('.worker-checkbox').each(function() {
            let row = $(this).closest('tr');

            if (!$(this).is(':checked')) {
                row.find('input, select').not('.worker-checkbox').prop('disabled', true);
            } else {
                row.find('input, select').not('.worker-checkbox').prop('disabled', false);
            }
        });

        $('.btn-attandace').on('click', function(e) {

            e.preventDefault();

            let isValid = true;
            let errorMessages = [];

            // if ($('#is_block_activity').is(':checked') || !$('#activity_name').val().trim()) {
            //     isValid = false;
            //     errorMessages.push(
            //         'Nama aktivitas harus diisi jika checkbox aktivitas pengerjaan block dicentang.');
            // }

            $('tbody tr').each(function() {
                let checkbox = $(this).find('.worker-checkbox');
                if (checkbox.is(':checked')) {
                    let durasiKerja = $(this).find('select[name="durasi_kerja"]').val();
                    let upah = $(this).find('input[name="upah"]').val();

                    if (!durasiKerja) {
                        isValid = false;
                        errorMessages.push(
                            `Durasi kerja harus dipilih untuk pekerja dengan ID ${checkbox.val()}.`);
                    }

                    if (!upah.trim()) {
                        isValid = false;
                        errorMessages.push(
                            `Upah harus diisi untuk pekerja dengan ID ${checkbox.val()}.`);
                    }
                }
            });

            if (!isValid) {
                alert('Validasi gagal:\n' + errorMessages.join('\n'));
                return;
            }

            let pageData = {
                blockID: $('#id').val(),
                activity: {
                    id: $('#id').val(),
                    is_block_activity: $('#is_block_activity').is(':checked') ? 1 : 0,
                    activity_name: $('#activity_name').val()
                },
                workers: []
            };

            $('tbody tr').each(function() {
                let checkbox = $(this).find('.worker-checkbox');
                let rowData = {
                    worker_id: checkbox.val(),
                    is_checked: checkbox.is(':checked') ? 1 : 0,
                    durasi_kerja: $(this).find('select[name="durasi_kerja"]').val(),
                    upah: $(this).find('input[name="upah"]').val(),
                    pinjaman: $(this).find('input[name="pinjaman"]').val()
                };
                console.log(rowData.length)
                pageData.workers.push(rowData);
            });

            // Debugging: Tampilkan data di console
            console.log(pageData);
            $.ajax({
                url: "{{ route('block-attendances.store') }}",
                method: 'POST',
                data: pageData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                success: function(response) {
                    alert('Data berhasil disimpan!');
                    console.log(response);
                },
                error: function(error) {
                    alert('Terjadi kesalahan saat mengirim data.');
                    console.error(error);
                }
            });
        });
    </script>
@endpush
