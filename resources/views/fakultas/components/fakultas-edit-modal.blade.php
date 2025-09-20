<div class="modal fade" id="editFakultasModal" tabindex="-1" aria-labelledby="editFakultasModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editFakultasModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Fakultas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFakultasForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_kode_fakultas" class="form-label">Kode Fakultas</label>
                        <input type="text" class="form-control" id="edit_kode_fakultas" name="kode_fakultas" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nama_fakultas" class="form-label">Nama Fakultas <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_fakultas" name="nama_fakultas" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>