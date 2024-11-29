<div class="modal fade" id="modal-add-payment">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="form-add-payment" action="{{ route('worker-payment.store') }}">
                @csrf
                <input type="hidden" name="project_id" id="project_id" value="{{ $result->id }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Tambah Pembayaran Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="attachment">Bukti Pembayaran</label>
                        <input type="file" class="filepond" id="attachment" name="attachment" placeholder="Bukti Pembayaran">
                    </div>
                    <div class="form-group">
                        <label for="transaction_date">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date" placeholder="Tanggal Pembayaran">
                    </div>
                    <div class="form-group">
                        <label for="week">Minggu</label>
                        <input type="number" class="form-control" id="week" name="week" placeholder="Minggu Ke">
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="number" class="form-control" id="total" name="total" placeholder="Total Pembayaran">
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