@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">
            <i class="fas fa-book me-2"></i>Pilihan Program Studi
        </h4>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form method="POST" action="{{ route('pendaftaran.pilih-prodi.post') }}">
            @csrf

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Silakan pilih program studi yang Anda minati. Pilihan ini dapat mempengaruhi proses seleksi.
            </div>

            <div class="mb-4">
                <label for="kode_program_studi" class="form-label h5">
                    <i class="fas fa-graduation-cap me-2"></i>Program Studi Pilihan <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-lg @error('kode_program_studi') is-invalid @enderror"
                    id="kode_program_studi" name="kode_program_studi" required>
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($programStudi as $prodi)
                    <option value="{{ $prodi->kode_program_studi }}" {{ old('kode_program_studi', $calonMahasiswa->
                        kode_program_studi) == $prodi->kode_program_studi ? 'selected' : '' }}>
                        {{ $prodi->nama_program_studi }} - {{ $prodi->fakultas->nama_fakultas }}
                    </option>
                    @endforeach
                </select>
                @error('kode_program_studi')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row" id="prodi-details">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Detail Program Studi</h6>
                            <p class="card-text text-muted">Pilih program studi untuk melihat detail</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Informasi Fakultas</h6>
                            <p class="card-text text-muted">Pilih program studi untuk melihat informasi fakultas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check me-2"></i>Simpan Pilihan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const prodiData = {!! json_encode($programStudi->keyBy('kode_program_studi')) !!};

document.getElementById('kode_program_studi').addEventListener('change', function() {
    const selectedProdi = prodiData[this.value];
    const prodiDetails = document.getElementById('prodi-details');
    
    if (selectedProdi) {
        prodiDetails.innerHTML = `
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-primary">${selectedProdi.nama_program_studi}</h6>
                        <p class="card-text">
                            <small class="text-muted">Akreditasi: ${selectedProdi.akreditasi || 'A'}</small><br>
                            <small class="text-muted">Jenjang: ${selectedProdi.jenjang || 'S1'}</small><br>
                            <small class="text-muted">Kuota: ${selectedProdi.kuota || '100'} mahasiswa</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-primary">${selectedProdi.fakultas.nama_fakultas}</h6>
                        <p class="card-text">
                            <small class="text-muted">Dean: ${selectedProdi.fakultas.dekan || 'Prof. Dr. John Doe'}</small><br>
                            <small class="text-muted">Lokasi: Kampus ${selectedProdi.fakultas.lokasi || 'Utama'}</small><br>
                            <small class="text-muted">Telp: ${selectedProdi.fakultas.telepon || '(021) 1234567'}</small>
                        </p>
                    </div>
                </div>
            </div>
        `;
    }
});

// Trigger change event jika sudah ada nilai selected
const selectedValue = document.getElementById('kode_program_studi').value;
if (selectedValue) {
    document.getElementById('kode_program_studi').dispatchEvent(new Event('change'));
}
</script>
@endsection