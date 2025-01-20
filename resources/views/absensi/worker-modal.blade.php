<div class="modal fade" id="modal-add-worker">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('worker-group.store') }}" method="POST" id="form-add-worker">
                @csrf
                <input type="hidden" name="activity_id" id="activity_id" value="{{ $activity->id }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Pekerja</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="worker_id">Tukang</label>
                        <select class="form-control" name="worker_id" id="worker_id"></select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <div>
                        <button type="button" class="btn btn-primary save-worker">Save</button>
                        <button type="button" class="btn btn-success save-next">Save and add next</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
