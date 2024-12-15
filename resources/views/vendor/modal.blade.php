<div class="modal fade" id="modal-add-vendor">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('vendor.store') }}" method="POST" id="form-add-vendor">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Vendor Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_vendor">Nama Vendor *</label>
                        <input type="text" class="form-control" id="nama_vendor" name="nama_vendor" placeholder="Nama Vendor" required>
                    </div>
                    <div class="form-group">
                        <label for="kontak">Kontak</label>
                        <input type="text" class="form-control" id="kontak" name="kontak" placeholder="Kontak" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat">
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