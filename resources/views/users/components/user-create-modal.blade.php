<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Tambah User Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_name" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="create_name" name="name" value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="create_email" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan email" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_password" class="form-label">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="create_password" name="password" placeholder="Masukkan password" required>
                                <div class="form-text">Minimal 8 karakter</div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_password_confirmation" class="form-label">Konfirmasi Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="create_password_confirmation"
                                    name="password_confirmation" placeholder="Konfirmasi password" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_role" class="form-label">Role <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="create_role"
                                    name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="administrator" {{ old('role')=='administrator' ? 'selected' : '' }}>
                                        Administrator</option>
                                    <option value="mahasiswa" {{ old('role')=='mahasiswa' ? 'selected' : '' }}>Calon
                                        Mahasiswa</option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_program_studi" class="form-label">Program Studi</label>
                                <select class="form-select @error('kode_program_studi') is-invalid @enderror"
                                    id="create_program_studi" name="kode_program_studi">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($programStudi as $prodi)
                                    <option value="{{ $prodi->kode_program_studi }}" {{
                                        old('kode_program_studi')==$prodi->kode_program_studi ? 'selected' : '' }}>
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
                    <div id="createCalonMahasiswaFields" style="display: none;">
                        <hr>
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Data Calon Mahasiswa
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="create_jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected' : ''
                                            }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : ''
                                            }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        id="create_tanggal_lahir" name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir') }}">
                                    @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="create_alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="create_alamat"
                                name="alamat" rows="2"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_no_telepon" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                        id="create_no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                                        placeholder="Contoh: 081234567890">
                                    @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="create_asal_sekolah" class="form-label">Asal Sekolah</label>
                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                                        id="create_asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                        placeholder="Masukkan asal sekolah">
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('create_role');
        const mahasiswaFields = document.getElementById('createCalonMahasiswaFields');

        function toggleMahasiswaFields() {
            if (roleSelect.value === 'mahasiswa') {
                mahasiswaFields.style.display = 'block';
            } else {
                mahasiswaFields.style.display = 'none';
                // Optional: clear values
                mahasiswaFields.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
            }
        }

        roleSelect.addEventListener('change', toggleMahasiswaFields);

        // Inisialisasi saat load
        toggleMahasiswaFields();
    });
</script>