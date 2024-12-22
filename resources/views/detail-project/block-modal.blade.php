<div class="modal fade" id="modal-add-block">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('block.store', $result->id) }}" method="POST" id="form-add-block">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Block</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="block">Block *</label>
                        <input type="text" class="form-control" id="block" name="block" placeholder="Block" required>
                    </div>
                    <div class="form-group">
                        <label for="type">type *</label>
                        <input type="text" class="form-control" id="type" name="type" placeholder="Type" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_id">Customer</label>
                        <div class="row">
                            <div class="col-7">
                                <select class="form-control" name="customer_id" id="customer_id"></select>
                            </div>
                            <div class="col-5">
                                <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#modal-add-customer">
                                    Tambah Customer
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="text" class="form-control" id="harga" name="harga" placeholder="Harga" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
                    </div>
                    <div class="form-group">
                        <label for="luas_tanah">Luas Tanah</label>
                        <input type="text" class="form-control" id="luas_tanah" name="luas_tanah" placeholder="Luas Tanah" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
                    </div>
                    <div class="form-group">
                        <label for="luas_bangunan">Luas Bangunan</label>
                        <input type="text" class="form-control" id="luas_bangunan" name="luas_bangunan" placeholder="Luas Bangunan" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
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