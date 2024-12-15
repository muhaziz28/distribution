<div class="modal fade" id="modal-add-payment-history">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payment.store', $result->id) }}" method="POST" id="form-add-payment-history">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Pembayaran Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="total">Total *</label>
                        <input class="form-control" name="total" id="total" type="number" />
                    </div>
                    <div class="form-group">
                        <label for="payment_date">Tanggal Pembayaran *</label>
                        <input type="text" class="form-control singlepicker" name="payment_date" id="payment_date" />
                    </div>
                    <div class="form-group">
                        <label for="attachment">Bukti Pembayaran</label>
                        <input class="filepond" name="file" id="file" type="file" />
                    </div>

                    <div class="form-group">
                        <label for="attachment">Jenis Pembayaran</label>
                        <select name="payment_type" id="payment_type" class="form-control form-select">
                            <option value="">Jenis Pembayaran</option>
                            <option value="dp">DP</option>
                            <option value="installment">Cicilan</option>
                            <!-- <option value="item_payment">Item</option> -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="note">Catatan</label>
                        <textarea class="form-control" name="note" id="note"></textarea>
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