<div class="modal fade" id="createProgramStudiModal" tabindex="-1" aria-labelledby="createProgramStudiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createProgramStudiModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Program Studi Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="createProgramStudiForm" action="{{ route('admin.program-studi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_kode_program_studi" class="form-label">Kode Program Studi <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('kode_program_studi') is-invalid @enderror"
                                    id="create_kode_program_studi" name="kode_program_studi"
                                    value="{{ old('kode_program_studi') }}" placeholder="Contoh: TI" required
                                    maxlength="10">
                                <div class="form-text">Kode unik (huruf kapital dan angka)</div>
                                @error('kode_program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_nama_program_studi" class="form-label">Nama Program Studi <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('nama_program_studi') is-invalid @enderror"
                                    id="create_nama_program_studi" name="nama_program_studi"
                                    value="{{ old('nama_program_studi') }}" placeholder="Contoh: Teknik Informatika"
                                    required maxlength="100">
                                @error('nama_program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_kode_fakultas" class="form-label">Fakultas <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('kode_fakultas') is-invalid @enderror"
                                    id="create_kode_fakultas" name="kode_fakultas" required>
                                    <option value="">Pilih Fakultas</option>
                                    @foreach($fakultas as $fak)
                                    <option value="{{ $fak->kode_fakultas }}" {{ old('kode_fakultas')==$fak->
                                        kode_fakultas ? 'selected' : '' }}>
                                        {{ $fak->nama_fakultas }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kode_fakultas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="create_jenjang" class="form-label">Jenjang <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('jenjang') is-invalid @enderror" id="create_jenjang"
                                    name="jenjang" required>
                                    <option value="">Pilih Jenjang</option>
                                    <option value="D3" {{ old('jenjang')=='D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('jenjang')=='S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('jenjang')=='S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('jenjang')=='S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('jenjang')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="create_biaya_pendaftaran" class="form-label">Biaya Pendaftaran <span
                                        class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control @error('biaya_pendaftaran') is-invalid @enderror"
                                    id="create_biaya_pendaftaran" name="biaya_pendaftaran"
                                    value="{{ old('biaya_pendaftaran', 0) }}" placeholder="0" required min="0">
                                @error('biaya_pendaftaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="create_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="create_deskripsi"
                            name="deskripsi" rows="3"
                            placeholder="Deskripsi singkat tentang program studi">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
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
</div>