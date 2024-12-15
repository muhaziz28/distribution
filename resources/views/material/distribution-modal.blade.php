<div class="modal fade" id="modal-distribution">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="form-distribution">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Distribusi Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bahan">Bahan</label>
                        <input type="text" class="form-control" id="bahan" name="bahan" readonly disabled>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="project_id">Project</label>
                                <select class="form-control" name="project_id" id="project_id"></select>
                            </div>
                            <div class="col-6">
                                <label for="block_id">Block</label>
                                <select class="form-control" name="block_id" id="block_id"></select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="distributed_qty">Qty</label>
                        <p id="max_qty"></p>
                        <input class="form-control" name="distributed_qty" id="distributed_qty" />
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