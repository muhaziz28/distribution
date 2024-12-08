<div class="modal fade" id="modal-add-customer">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customer.store') }}" method="POST" id="form-add-customer">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Customer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Customer *</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Customer" required>
                    </div>
                    <div class="form-group">
                        <label for="np_hp">Kontak</label>
                        <input type="text" class="form-control" id="np_hp" name="no_hp" placeholder="No HP" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)">
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