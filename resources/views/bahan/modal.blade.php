<div class="modal fade" id="modal-add-bahan">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bahan.store') }}" method="POST" id="form-add-bahan">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Bahan Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_bahan">Nama Bahan</label>
                        <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" placeholder="Nama bahan">
                    </div>
                    <div class="form-group">
                        <label for="qty">Qty</label>
                        <input type="number" class="form-control" id="qty" name="qty" placeholder="Qty">
                    </div>
                    <div class="form-group">
                        <label for="satuan_id">Satuan</label>
                        <select class="form-control" name="satuan_id" id="satuan_id"></select>
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