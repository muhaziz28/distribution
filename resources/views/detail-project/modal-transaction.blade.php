<div class="modal fade" id="modal-add-project">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('project.store') }}" method="POST" id="form-add-project">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Add New Project</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun_anggaran">Tahun Anggaran</label>
                        <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran"
                            placeholder="Tahun Anggaran.." min="1900" max="2100">
                    </div>
                    <div class="form-group">
                        <label for="kegiatan">Kegiatan</label>
                        <input type="text" class="form-control" id="kegiatan" name="kegiatan"
                            placeholder="Kegiatan...">
                    </div>
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                            placeholder="Pekerjaan...">
                    </div>
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                            placeholder="Lokasi...">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="process">Process</option>
                            <option value="finished">Finished</option>
                        </select>
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