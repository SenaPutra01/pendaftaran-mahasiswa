{{-- <div class="modal fade" id="createFakultasModal" tabindex="-1" aria-labelledby="createFakultasModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createFakultasModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Fakultas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createFakultasForm" action="{{ route('admin.fakultas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_fakultas" class="form-label">Kode Fakultas <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_fakultas') is-invalid @enderror"
                            id="kode_fakultas" name="kode_fakultas" required>
                        @error('kode_fakultas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nama_fakultas" class="form-label">Nama Fakultas <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_fakultas') is-invalid @enderror"
                            id="nama_fakultas" name="nama_fakultas" required>
                        @error('nama_fakultas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}


<!-- Modal Create Fakultas -->
<div class="modal fade" id="createFakultasModal" tabindex="-1" aria-labelledby="createFakultasModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createFakultasModalLabel">
                    <i class="fas fa-plus me-2"></i> Tambah Fakultas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createFakultasForm" action="{{ route('admin.fakultas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Nama Fakultas -->
                    <div class="mb-3">
                        <label for="nama_fakultas" class="form-label">Nama Fakultas <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_fakultas') is-invalid @enderror"
                            id="nama_fakultas" name="nama_fakultas" value="{{ old('nama_fakultas') }}" required>
                        @error('nama_fakultas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi Fakultas -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                            name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kode Fakultas akan otomatis di-generate, tampil optional -->
                    <div class="mb-3">
                        <label class="form-label">Kode Fakultas</label>
                        <input type="text" class="form-control" value="(Akan dibuat otomatis)" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>