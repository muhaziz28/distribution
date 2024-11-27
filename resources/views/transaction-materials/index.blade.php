@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 items-center">
                <div class="col">
                    <h1 class="m-0">Transaksi Baru</h1>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="m-0"> Upload Bukti Transaksi</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('uploads.save') }}" method="POST">
                                @csrf

                                <input type="file" name="file" class="filepond" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="m-0">Bahan</h4>
                        </div>

                        <table class="table table-bordered" id="table-belanja">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bahan</th>
                                    <th>QTY</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="card-footer">
                            <div class="float-end">
                                <h5>Total: <span id="total_belanja"></span></h5>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary float-end mt-3 btn_process">Proses Belanja</a>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Pilih Bahan
                        </div>

                        <div class="card-body">
                            <form action="" id="bahan-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Bahan</label>
                                    <select class="form-control form-select" id="bahan-select" name="nama_barang">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="qty" class="form-label">QTY</label>
                                    <input type="number" class="form-control" id="total_qty" name="total_qty">
                                </div>
                                <div class="mb-3">
                                    <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                    <input type="number" class="form-control" id="harga_satuan" name="harga_satuan">
                                </div>

                                <button type="submit" class="btn btn-primary"><i class="fa fa-cart-plus mr-2"></i> Tambah</button>
                            </form>
                        </div>
                    </div>
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

    function formatCurrency(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function removeRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.total-td').forEach(function(element) {
            let value = element.textContent.replace(/[^\d,-]/g, '').replace(',', '.');

            total += parseFloat(value)
        });

        document.getElementById('total_belanja').textContent = formatCurrency(total)
    }

    $(document).ready(function() {
        $('#bahan-select').select2({
            placeholder: 'Pilih Bahan',
            ajax: {
                url: "{{ route('bahan.data') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data.data.map(function(item) {
                            console.log(data)
                            return {
                                text: item.nama_bahan,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true,
            }
        });

        $('#bahan-select').on('select2:select', function(e) {
            var data = e.params.data;

            $('#qty').val(data.qty);
            $('#harga_satuan').val(data.harga_satuan);
        });

        $('tbody').on('click', '.btn-success', function() {
            increaseQty(this);
        });

        $('tbody').on('click', '.btn-warning', function() {
            decreaseQty(this);
        });

        $('#bahan-form').submit(function(e) {
            e.preventDefault();

            var namaBahan = $('#bahan-select').select2('data')[0].text;
            var qty = $('#total_qty').val();
            var hargaSatuan = $('#harga_satuan').val();

            if (qty == '' || qty == 0 || qty == null) {
                alert('QTY tidak boleh kosong.');
                return;
            }

            var total = qty * hargaSatuan;

            var rowNumber = $('tbody tr').length + 1

            var newRow = "<tr>" +
                "<td>" + rowNumber + "</td>" +
                "<td>" + namaBahan + "</td>" +
                "<td><input type='number' class='form-control qty-input' value='" + qty + "' min='1' /></td>" +
                "<td>" + formatCurrency(hargaSatuan) + "</td>" +
                "<td class='total-td'>" + formatCurrency(total) + "</td>" +
                "<td><button type='button' class='btn btn-default' onclick='removeRow(this)'><i class='fas fa-trash text-danger'></i></button></td>" +
                "</tr>";

            $('tbody').append(newRow);

            updateTotal();

            $('#bahan-select').val(null).trigger('change');
            $('#qty').val('');
            $('#total_qty').val('');
            $('#harga_satuan').val('');
        });

        $('tbody').on('input', '.qty-input', function() {
            const $row = $(this).closest('tr');
            const qty = parseFloat($(this).val());
            const hargaSatuan = parseFloat($row.find('td:nth-child(4)').text().replace(/[^\d,-]/g, '').replace(',', '.'))
            const $totalCell = $row.find('.total-td');

            if (isNaN(qty) || qty <= 0) {
                alert('QTY tidak valid. Harus angka positif.');
                $(this).val(1);
                return;
            }

            const total = qty * hargaSatuan;
            const formattedTotal = formatCurrency(total);
            $totalCell.text(formattedTotal);

            updateTotal();
        });
    })
</script>
@endpush