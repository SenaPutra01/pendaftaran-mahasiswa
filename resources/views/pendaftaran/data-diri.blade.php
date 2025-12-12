@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">
            <i class="fas fa-user-circle me-2"></i>Data Diri Calon Mahasiswa
        </h4>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form method="POST" action="{{ route('pendaftaran.data-diri.post') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <img id="fotoPreview" src="{{ asset('images/default-profile.png') }}" alt="Preview Foto"
                        class="img-fluid rounded mb-3" style="max-height: 200px;">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Upload Foto</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto"
                            name="foto" accept="image/*" onchange="previewImage(this)">
                        <small class="form-text text-muted">Maksimal 2MB, format: jpeg, png, jpg, gif</small>
                        @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                                    name="nik" value="{{ old('nik', $calonMahasiswa->nik) }}" required
                                    placeholder="16 digit NIK">
                                @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $calonMahasiswa->jenis_kelamin) ==
                                        'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $calonMahasiswa->jenis_kelamin) ==
                                        'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    id="tempat_lahir" name="tempat_lahir"
                                    value="{{ old('tempat_lahir', $calonMahasiswa->tempat_lahir) }}" required
                                    placeholder="Kota tempat lahir">
                                @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $calonMahasiswa->tanggal_lahir ? $calonMahasiswa->tanggal_lahir->format('Y-m-d') : '') }}"
                                    required>
                                @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                        <select class="form-select @error('agama') is-invalid @enderror" id="agama" name="agama"
                            required>
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ old('agama', $calonMahasiswa->agama) == 'Islam' ? 'selected' : ''
                                }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $calonMahasiswa->agama) == 'Kristen' ? 'selected' :
                                '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $calonMahasiswa->agama) == 'Katolik' ? 'selected' :
                                '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $calonMahasiswa->agama) == 'Hindu' ? 'selected' : ''
                                }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $calonMahasiswa->agama) == 'Buddha' ? 'selected' : ''
                                }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $calonMahasiswa->agama) == 'Konghucu' ? 'selected'
                                : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <h5 class="mb-3"><i class="fas fa-phone-alt me-2"></i>Kontak</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">No. Telepon <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                            id="no_telepon" name="no_telepon"
                            value="{{ old('no_telepon', $calonMahasiswa->no_telepon) }}" required
                            placeholder="08xxxxxxxxxx">
                        @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="asal_sekolah" class="form-label">Asal Sekolah <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                            id="asal_sekolah" name="asal_sekolah"
                            value="{{ old('asal_sekolah', $calonMahasiswa->asal_sekolah) }}" required
                            placeholder="Nama sekolah asal">
                        @error('asal_sekolah')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                    required
                    placeholder="Alamat lengkap tempat tinggal">{{ old('alamat', $calonMahasiswa->alamat) }}</textarea>
                @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr>

            <h5 class="mb-3"><i class="fas fa-users me-2"></i>Data Orang Tua</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_orang_tua" class="form-label">Nama Orang Tua <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_orang_tua') is-invalid @enderror"
                            id="nama_orang_tua" name="nama_orang_tua"
                            value="{{ old('nama_orang_tua', $calonMahasiswa->nama_orang_tua) }}" required
                            placeholder="Nama lengkap orang tua">
                        @error('nama_orang_tua')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="pekerjaan_orang_tua" class="form-label">Pekerjaan Orang Tua <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pekerjaan_orang_tua') is-invalid @enderror"
                            id="pekerjaan_orang_tua" name="pekerjaan_orang_tua"
                            value="{{ old('pekerjaan_orang_tua', $calonMahasiswa->pekerjaan_orang_tua) }}" required
                            placeholder="Pekerjaan orang tua">
                        @error('pekerjaan_orang_tua')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="penghasilan_orang_tua" class="form-label">Penghasilan Orang Tua <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('penghasilan_orang_tua') is-invalid @enderror"
                            id="penghasilan_orang_tua" name="penghasilan_orang_tua" required>
                            <option value="">Pilih Penghasilan</option>
                            <option value="< Rp 1.000.000" {{ old('penghasilan_orang_tua', $calonMahasiswa->
                                penghasilan_orang_tua) == '< Rp 1.000.000' ? 'selected' : '' }}>
                                    < Rp 1.000.000</option>
                            <option value="Rp 1.000.000 - Rp 3.000.000" {{ old('penghasilan_orang_tua',
                                $calonMahasiswa->penghasilan_orang_tua) == 'Rp 1.000.000 - Rp 3.000.000' ? 'selected' :
                                '' }}>Rp 1.000.000 - Rp 3.000.000</option>
                            <option value="Rp 3.000.000 - Rp 5.000.000" {{ old('penghasilan_orang_tua',
                                $calonMahasiswa->penghasilan_orang_tua) == 'Rp 3.000.000 - Rp 5.000.000' ? 'selected' :
                                '' }}>Rp 3.000.000 - Rp 5.000.000</option>
                            <option value="> Rp 5.000.000" {{ old('penghasilan_orang_tua', $calonMahasiswa->
                                penghasilan_orang_tua) == '> Rp 5.000.000' ? 'selected' : '' }}>> Rp 5.000.000</option>
                        </select>
                        @error('penghasilan_orang_tua')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="no_telepon_orang_tua" class="form-label">No. Telepon Orang Tua <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_telepon_orang_tua') is-invalid @enderror"
                            id="no_telepon_orang_tua" name="no_telepon_orang_tua"
                            value="{{ old('no_telepon_orang_tua', $calonMahasiswa->no_telepon_orang_tua) }}" required
                            placeholder="08xxxxxxxxxx">
                        @error('no_telepon_orang_tua')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Simpan Data Diri
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('nik').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 16);
});

document.getElementById('no_telepon').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 15);
});

document.getElementById('no_telepon_orang_tua').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 15);
});
</script>
@endsection