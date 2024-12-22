@extends('layouts.app')

@section('title', "Pembayaran Upah Pekerja")

@section("content")
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="d-flex">
                    <button onclick="history.back()" class="btn btn-default mr-3">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                    <h1 class="m-0"></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="col">
                <div class="card">
                    <form action="{{ route('worker-payment.store') }}" method="post" id="form-add-worker-payment">
                        @csrf
                        <input type="hidden" name="block_id" id="block_id" value="{{ $blockID }}">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="payment_date">Tanggal Pembayaran *</label>
                                        <input type="text" class="form-control singlepicker" id="payment_date" name="payment_date" placeholder="Tanggal pembayaran" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="week">Minggu Ke *</label>
                                        <input type="text" class="form-control" id="week" name="week" placeholder="Minggu ke" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="attachment">Bukti Pembayaran</label>
                                <input type="file" name="file" id="file" class="filepond" />
                            </div>
                            <table class="table table-bordered table-striped mt-5">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Pekerja</th>
                                        <th>Durasi Kerja</th>
                                        <th>Upah</th>
                                        <th>Pinjaman</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activity as $act)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td colspan="5">
                                            {{ $act->is_block_activity == 1 ? "Pengerjaan Blok" : $act->activity_name }}
                                        </td>
                                    </tr>
                                    @foreach ($act->workerGroups as $group)
                                    <tr>
                                        <td></td>
                                        <td>{{ $group->tukang->nama_tukang }}</td>
                                        <td class="durasi_kerja">{{ $group->workerAttendances[0]->durasi_kerja ?? 0 }}</td>
                                        <input type="hidden" name="worker_group_id[]" value="{{ $group->id }}">
                                        <td>
                                            <input class="form-control upah" type="number" name="upah[]" placeholder="0" required />
                                        </td>
                                        <td>
                                            <input class="form-control pinjaman" type="number" name="pinjaman[]" placeholder="0" />
                                        </td>
                                        <td>
                                            <span class="total">0</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-3">Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
    document.addEventListener("input", function(e) {
        if (e.target.matches(".upah, .pinjaman")) {
            const row = e.target.closest("tr")
            const durasiKerja = parseFloat(row.querySelector(".durasi_kerja").textContent) || 0
            const upah = parseFloat(row.querySelector(".upah").value) || 0
            const pinjaman = parseFloat(row.querySelector(".pinjaman").value) || 0

            const total = (durasiKerja * upah) - pinjaman

            row.querySelector(".total").textContent = total.toFixed(2)
        }
    });

    $('#form-add-worker-payment').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('block_id', document.querySelector('#block_id').value);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('week', document.querySelector('#week').value);
        formData.append('payment_date', document.querySelector('#payment_date').value);

        const hiddenInput = document.querySelector('.filepond--data input[type="hidden"]');

        if (hiddenInput && hiddenInput.value) {
            const filePath = hiddenInput.value;
            formData.append('file', filePath)
        } else {
            console.warn('Tidak ada file');
        }

        const upahInputs = document.querySelectorAll('input[name="upah[]"]')
        const pinjamanInputs = document.querySelectorAll('input[name="pinjaman[]"]')
        const workerGroupIds = document.querySelectorAll('input[name="worker_group_id[]"]')

        upahInputs.forEach((input, index) => {
            const upahValue = input.value || 0;
            0
            const pinjamanValue = pinjamanInputs[index].value || 0;
            const workerGroupId = workerGroupIds[index].value

            formData.append(`payments[${index}][worker_group_id]`, workerGroupId)
            formData.append(`payments[${index}][upah]`, upahValue)
            formData.append(`payments[${index}][pinjaman]`, pinjamanValue)
        });

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                if (response.success) {
                    $('#form-add-worker-payment')[0].reset();
                    toastr.success(response.message);
                    clearFilePond('.filepond');
                    const hiddenInput = document.querySelector('.filepond--data input[type="hidden"]');
                    if (hiddenInput) {
                        hiddenInput.value = '';
                    }
                    $('#attachment').val('');
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
</script>
@endpush