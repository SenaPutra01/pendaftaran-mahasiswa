{{-- <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Data User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <input type="hidden" id="edit_calon_mahasiswa_id" name="calon_mahasiswa_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit_name" name="name" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="edit_email" name="email" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="edit_password" name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah">
                                <div class="form-text">Minimal 8 karakter</div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="edit_password_confirmation"
                                    name="password_confirmation" placeholder="Konfirmasi password">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_role" class="form-label">Role <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="edit_role"
                                    name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="administrator">Administrator</option>
                                    <option value="calon_mahasiswa">Calon Mahasiswa</option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_program_studi" class="form-label">Program Studi</label>
                                <select class="form-select @error('kode_program_studi') is-invalid @enderror"
                                    id="edit_program_studi" name="kode_program_studi">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($programStudi as $prodi)
                                    <option value="{{ $prodi->kode_program_studi }}">
                                        {{ $prodi->nama_program_studi }} ({{ $prodi->jenjang }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('kode_program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Data Calon Mahasiswa -->
                    <div id="editCalonMahasiswaFields" style="display: none;">
                        <hr>
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Data Calon Mahasiswa
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="edit_jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        id="edit_tanggal_lahir" name="tanggal_lahir">
                                    @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="edit_alamat"
                                name="alamat" rows="2"></textarea>
                            @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_no_telepon" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                        id="edit_no_telepon" name="no_telepon">
                                    @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_asal_sekolah" class="form-label">Asal Sekolah</label>
                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                                        id="edit_asal_sekolah" name="asal_sekolah">
                                    @error('asal_sekolah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
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
</div> --}}



<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Data User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <input type="hidden" id="edit_calon_mahasiswa_id" name="calon_mahasiswa_id">

                    <!-- Basic fields: name, email, password -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
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
                                <label for="edit_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="edit_password" name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="edit_password_confirmation"
                                    name="password_confirmation" placeholder="Konfirmasi password">
                            </div>
                        </div>
                    </div>

                    <!-- Role & Program Studi -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_role" class="form-label">Role <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="administrator">Administrator</option>
                                    <option value="mahasiswa">Calon Mahasiswa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_program_studi" class="form-label">Program Studi</label>
                                <select class="form-select" id="edit_program_studi" name="kode_program_studi">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($programStudi as $prodi)
                                    <option value="{{ $prodi->kode_program_studi }}">
                                        {{ $prodi->nama_program_studi }} ({{ $prodi->jenjang }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Data calon mahasiswa -->
                    <div id="editCalonMahasiswaFields" style="display: none;">
                        <hr>
                        <h6 class="text-primary mb-3"><i class="fas fa-graduation-cap me-2"></i>Data Calon Mahasiswa
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="edit_tanggal_lahir"
                                        name="tanggal_lahir">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_no_telepon" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" id="edit_no_telepon" name="no_telepon">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_asal_sekolah" class="form-label">Asal Sekolah</label>
                                    <input type="text" class="form-control" id="edit_asal_sekolah" name="asal_sekolah">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fas fa-times me-1"></i> Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>