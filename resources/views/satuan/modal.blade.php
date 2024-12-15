<div class="modal fade" id="modal-add-satuan">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('satuan.store') }}" method="POST" id="form-add-satuan">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Satuan Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="satuan">Nama Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Nama Satuan">
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