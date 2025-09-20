<!-- Detail Modal -->
<!-- Modal Detail -->
<div class="modal fade" id="detailCalonModal" tabindex="-1" aria-labelledby="detailCalonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailCalonModalLabel">Detail Calon Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="detail_foto" src="" alt="Foto" class="img-fluid rounded mb-3"
                            style="max-height: 200px;">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama Lengkap</th>
                                <td id="detail_nama"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="detail_email"></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td id="detail_jenis_kelamin"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td id="detail_tanggal_lahir"></td>
                            </tr>
                            <tr>
                                <th>Usia</th>
                                <td id="detail_usia"></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td id="detail_alamat"></td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td id="detail_no_telepon"></td>
                            </tr>
                            <tr>
                                <th>Program Studi</th>
                                <td id="detail_prodi"></td>
                            </tr>
                            <tr>
                                <th>Asal Sekolah</th>
                                <td id="detail_asal"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Daftar</th>
                                <td id="detail_tanggal"></td>
                            </tr>
                            <tr>
                                <th>Status Verifikasi</th>
                                <td id="detail_status"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
{{-- <div class="modal fade" id="editCalonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editCalonForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Edit Calon Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" id="edit_nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email">
                    </div>
                    <div class="mb-3">
                        <label for="edit_program_studi" class="form-label">Program Studi</label>
                        <select class="form-select" name="kode_program_studi" id="edit_program_studi" required>
                            @foreach($programStudi as $prodi)
                            <option value="{{ $prodi->kode_program_studi }}">{{ $prodi->nama_program_studi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_asal" class="form-label">Asal Sekolah</label>
                        <input type="text" class="form-control" name="asal_sekolah" id="edit_asal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div> --}}

<!-- Modal Edit -->
<div class="modal fade" id="editCalonModal" tabindex="-1" aria-labelledby="editCalonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editCalonModalLabel">Edit Data Calon Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCalonForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama" name="nama_lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_jenis_kelamin" class="form-label">Jenis Kelamin <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_tanggal_lahir" class="form-label">Tanggal Lahir <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_no_telepon" class="form-label">No. Telepon <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_no_telepon" name="no_telepon" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_program_studi" class="form-label">Program Studi <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_program_studi" name="kode_program_studi" required>
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($programStudi as $prodi)
                                    <option value="{{ $prodi->kode_program_studi }}">
                                        {{ $prodi->nama_program_studi }} ({{ $prodi->fakultas->nama_fakultas }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_asal" class="form-label">Asal Sekolah <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_asal" name="asal_sekolah" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="edit_foto" name="foto" accept="image/*">
                                <small class="form-text text-muted">Maksimal 2MB, format: jpeg, png, jpg, gif</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Saat Ini</label>
                        <div>
                            <img id="edit_foto_preview" src="" alt="Foto saat ini" class="img-thumbnail"
                                style="max-height: 150px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Verifikasi Modal -->
{{-- <div class="modal fade" id="verifikasiCalonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="verifikasiCalonForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Verifikasi Calon Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin memverifikasi calon mahasiswa <strong id="verif_nama"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ya, Verifikasi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div> --}}

<!-- Modal Verifikasi -->
<div class="modal fade" id="verifikasiCalonModal" tabindex="-1" aria-labelledby="verifikasiCalonModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="verifikasiCalonModalLabel">Verifikasi Calon Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="verifikasiCalonForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin memverifikasi calon mahasiswa berikut?</p>
                    <p><strong>Nama:</strong> <span id="verif_nama"></span></p>
                    <div class="mb-3">
                        <label for="catatan_verifikasi" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="catatan_verifikasi" name="catatan_verifikasi" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Batalkan Verifikasi -->
<div class="modal fade" id="batalkanVerifikasiModal" tabindex="-1" aria-labelledby="batalkanVerifikasiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="batalkanVerifikasiModalLabel">Batalkan Verifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="batalkanVerifikasiForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan verifikasi calon mahasiswa berikut?</p>
                    <p><strong>Nama:</strong> <span id="batalkan_verif_nama"></span></p>
                    <div class="mb-3">
                        <label for="alasan_pembatalan" class="form-label">Alasan Pembatalan (Opsional)</label>
                        <textarea class="form-control" id="alasan_pembatalan" name="alasan_pembatalan" rows="3"
                            placeholder="Tambahkan alasan pembatalan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Batalkan Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>