// import './bootstrap';
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';

let pond = null;
const inputElement = document.querySelector('input[type="file"].filepond');
if (inputElement) {
    console.log("Initializing FilePond...");
    pond = FilePond.create(inputElement).setOptions({
        server: {
            process: '/uploads/process',
            revert: "/uploads/revert",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    });
}



$('.btn_process').on('click', function(e) {
    e.preventDefault();

    let belanjaData = [];
    let purchase = {
        vendor_id: $('#vendor_id').select2('data')[0].id,
        project_id: $('#project_id').val()
    }

    // Ambil data dari tabel
    $('#table-belanja tbody tr').each(function() {
        const namaBahan = $(this).find('td:nth-child(2)').text().trim();
        const qty = $(this).find('.qty-input').val();
        const hargaSatuan = $(this).find('td:nth-child(4)').text().replace(/[^\d,-]/g, '').replace(',', '.');
        const total = $(this).find('td:nth-child(5)').text().replace(/[^\d,-]/g, '').replace(',', '.');
        const bahanId = $(this).find('input[name="bahan_id"]').val(); // Ambil bahan_id dari input tersembunyi

        belanjaData.push({
            bahan_id: bahanId,
            nama_bahan: namaBahan,
            qty: parseFloat(qty),
            harga_satuan: parseFloat(hargaSatuan),
            total: parseFloat(total)
        });
    });

    console.log("payload");
    console.log(belanjaData);

    // Persiapkan FormData untuk pengiriman
    var formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('belanja', JSON.stringify(belanjaData));
    formData.append('purchase', JSON.stringify(purchase));
    const hiddenInput = document.querySelector('.filepond--data input[type="hidden"]');

    if (hiddenInput && hiddenInput.value) {
        const filePath = hiddenInput.value;
        
        formData.append('file', filePath)
    } else {
        console.warn('File tidak ditemukan');
    }

    $.ajax({
        url: "/transaction-materials/store", 
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.message);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Terjadi kesalahan saat menyimpan data.');
        }
    });
});
