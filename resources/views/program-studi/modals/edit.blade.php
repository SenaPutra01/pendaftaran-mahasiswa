<div class="modal fade" id="editProgramStudiModal" tabindex="-1" aria-labelledby="editProgramStudiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editProgramStudiModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Program Studi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProgramStudiForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_kode_program_studi" name="kode_program_studi">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama_program_studi" class="form-label">Nama Program Studi <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('nama_program_studi') is-invalid @enderror"
                                    id="edit_nama_program_studi" name="nama_program_studi" required maxlength="100">
                                @error('nama_program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_kode_fakultas" class="form-label">Fakultas <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('kode_fakultas') is-invalid @enderror"
                                    id="edit_kode_fakultas" name="kode_fakultas" required>
                                    <option value="">Pilih Fakultas</option>
                                    @foreach($fakultas as $fak)
                                    <option value="{{ $fak->kode_fakultas }}">
                                        {{ $fak->nama_fakultas }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kode_fakultas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_jenjang" class="form-label">Jenjang <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('jenjang') is-invalid @enderror" id="edit_jenjang"
                                    name="jenjang" required>
                                    <option value="">Pilih Jenjang</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                @error('jenjang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_biaya_pendaftaran" class="form-label">Biaya Pendaftaran <span
                                        class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control @error('biaya_pendaftaran') is-invalid @enderror"
                                    id="edit_biaya_pendaftaran" name="biaya_pendaftaran" required min="0">
                                @error('biaya_pendaftaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="edit_deskripsi"
                            name="deskripsi" rows="3"></textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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