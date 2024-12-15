@extends("layouts.app")

@section('title', "Pembayaran Item Tambahan")

@section("content")
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 row">
                    <a href="{{ route('block.detail', $block->id) }}" class="btn btn-default btn-sm mr-3">
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
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Tanggal Transaksi</label>
                                        <input type="text" name="payment_date" id="payment_date" class="form-control singlepicker" placeholder="Tanggal Transaksi" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="attachment">Bukti Pembayaran</label>
                                        <input class="filepond" name="file" id="file" type="file" />
                                    </div>
                                </div>
                            </div>
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
                        <input type="hidden" name="id" id="id" value="{{ $block->id }}">
                        <table class="table table-bordered" id="table-belanja">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Item</th>
                                    <th>Deskripsi</th>
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
                    <button type="submit" class="btn btn-primary float-end mt-3 additional-item-process">Proses Belanja</button>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Item
                        </div>

                        <div class="card-body">
                            <form action="" id="item-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="item_name" class="form-label">Nama Item *</label>
                                    <input class="form-control form-select" id="item_name" name="item_name" placeholder="Nama Item" required>
                                </div>
                                <div class="mb-3">
                                    <label for="item_description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control form-select" id="item_description" name="item_description" placeholder="Deskripsi"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="number" class="form-control" id="total" name="total" required>
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
        $('tbody').on('click', '.btn-success', function() {
            increaseQty(this);
        });

        $('tbody').on('click', '.btn-warning', function() {
            decreaseQty(this);
        });

        $('#item-form').submit(function(e) {
            e.preventDefault();

            var itemName = $('#item_name').val()
            var itemDescription = $('#item_description').val()
            var total = $('#total').val()

            var rowNumber = $('tbody tr').length + 1
            var newRow = "<tr>" +
                "<td>" + rowNumber + "</td>" +
                "<td>" + itemName + "</td>" +
                "<td>" + itemDescription + "</td>" +
                "<td class='total-td'>" + formatCurrency(total) + "</td>" +
                "<td><button type='button' class='btn btn-default' onclick='removeRow(this)'><i class='fas fa-trash text-danger'></i></button></td>" +
                "</tr>";


            $('tbody').append(newRow);
            updateTotal();

            $('#total').val('');
            $('#item_name').val('');
            $('#item_description').val('');
        });
    })

    $(".additional-item-process").on("click", function(e) {
        e.preventDefault();
        let belanjaData = [];
        let paymentDate = $("#payment_date").val()
        $("#table-belanja tbody tr").each(function() {
            const itemName = $(this).find("td:nth-child(2)").text().trim();
            const itemDescription = $(this).find("td:nth-child(3)").text().trim();
            const total = $(this)
                .find("td:nth-child(4)")
                .text()
                .replace(/[^\d,-]/g, "")
                .replace(",", ".");

            belanjaData.push({
                item_name: itemName,
                item_description: itemDescription,
                total: parseFloat(total),
            });
        });

        console.log("payload");
        console.log(belanjaData);
        console.log(paymentDate)

        var formData = new FormData();
        formData.append(
            "_token",
            document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
        );
        formData.append("paymentDate", paymentDate);
        const blockID = $("#id").val();
        formData.append("blockID", blockID);
        formData.append("items", JSON.stringify(belanjaData));
        const hiddenInput = document.querySelector(
            '.filepond--data input[type="hidden"]'
        );

        if (hiddenInput && hiddenInput.value) {
            const filePath = hiddenInput.value;

            formData.append("file", filePath);
        } else {
            console.warn("File tidak ditemukan");
        }

        $.ajax({
            url: "{{ route('payment.additionalItemStore') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                if (response.success) {
                    toastr.success(response.message);
                    $("#table-belanja tbody").empty();
                    clearFilePond('.filepond');
                    const hiddenInput = document.querySelector('.filepond--data input[type="hidden"]');
                    if (hiddenInput) {
                        hiddenInput.value = '';
                    }
                    $("#total_belanja").text("0");
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                toastr.error("Terjadi kesalahan saat menyimpan data.");
            },
        });
    });
</script>
@endpush