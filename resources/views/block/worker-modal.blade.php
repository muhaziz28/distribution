<div class="modal fade" id="modal-add-worker">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- {{ route('block-tukang.store', $blockTukangId) }} --}}
            <form action="{{ route('block-tukang.store', $result->id) }}" method="POST" id="form-add-worker">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Pekerja</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="worker_id">Tukang</label>
                        <div class="row">
                            <div class="col-7">
                                <select class="form-control" name="worker_id" id="worker_id"></select>
                            </div>
                            <div class="col-5">
                                <button type="button" class="btn btn-info w-100" data-toggle="modal" data-target="#modal-add-tukang">
                                    <i class="fa fa-plus-circle mr-2"></i>
                                    Tambah Tukang
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="join_date">Tanggal Bergabung</label>
                        <input type="text" class="form-control singlepicker" name="join_date" id="join_date" required />
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary simpan">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>