<div class="modal fade" id="modal-return">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="form-return" action="">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Retur</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item">Item</label>
                        <input type="text" class="form-control" id="item" name="item" readonly disabled>
                    </div>
                    <div class="form-group">
                        <label for="returned_qty">Qty</label>
                        <p id="max_qty"></p>
                        <input class="form-control" name="returned_qty" id="returned_qty" />
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