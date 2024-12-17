<div class="modal fade" id="modal-add-tukang">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tukang.store') }}" method="POST" id="form-add-tukang">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Add New Tukang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_tukang">Nama Tukang</label>
                        <input type="text" class="form-control" id="nama_tukang" name="nama_tukang"
                            placeholder="Nama tukang..">
                    </div>
                    <div class="form-group">
                        <label for="no_hp">Nomor Hp</label>
                        <input type="number" class="form-control" id="no_hp" name="no_hp" placeholder="Nomor Hp">
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