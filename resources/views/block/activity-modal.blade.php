<div class="modal fade" id="modal-add-activity">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('activity.store') }}" method="POST" id="form-add-activity">
                @csrf
                <input type="hidden" name="block_id" id="block_id" value="{{ $result->id }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Pekerjaan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="worker_id">Pekerjaan</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_block_activity" id="is_block_activity"
                                class="worker-checkbox">
                            <label for="is_block_activity" class="form-check-label">Pengerjaan Blok</label>
                        </div>
                        <input type="text" class="form-control my-2" name="activity_name" id="activity_name" />
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