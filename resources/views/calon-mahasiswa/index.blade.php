@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-users me-2"></i>Data Pendaftar (Calon Mahasiswa)
    </h1>
    <div>
        <a href="{{ route('admin.calon-mahasiswa.export') }}" class="btn btn-success me-2">
            <i class="fas fa-download me-1"></i> Export Data
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pendaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalPendaftar) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pendaftar Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendaftarHariIni) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pendaftar Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendaftarBulanIni) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<!-- Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-filter me-2"></i>Filter Data
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.calon-mahasiswa.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="program_studi" class="form-label">Program Studi</label>
                        <select class="form-select" id="program_studi" name="program_studi">
                            <option value="">Semua Program Studi</option>
                            @foreach($programStudi as $prodi)
                            <option value="{{ $prodi->kode_program_studi }}" {{ request('program_studi')==$prodi->
                                kode_program_studi ? 'selected' : '' }}>
                                {{ $prodi->nama_program_studi }} ({{ $prodi->fakultas->nama_fakultas }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                            value="{{ request('tanggal_mulai') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                            value="{{ request('tanggal_selesai') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Cari nama, email, atau sekolah..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.calon-mahasiswa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card shadow">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-list me-2"></i>Daftar Calon Mahasiswa
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="calonMahasiswaTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Program Studi</th>
                        <th>Asal Sekolah</th>
                        <th>Tanggal Daftar</th>
                        <th>Status Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calonMahasiswa as $calon)
                    <tr>
                        <td>{{ $loop->iteration + ($calonMahasiswa->currentPage() - 1) * $calonMahasiswa->perPage() }}
                        </td>
                        <td>{{ $calon->nama_lengkap }}</td>
                        <td>{{ $calon->user->email ?? '-' }}</td>
                        <td>{{ $calon->programStudi->nama_program_studi ?? '-' }}</td>
                        <td>{{ $calon->asal_sekolah }}</td>
                        <td>{{ $calon->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($calon->status_verifikasi == 'terverifikasi')
                            <span class="badge bg-success">Terverifikasi</span>
                            @elseif($calon->status_verifikasi == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                            @else
                            <span class="badge bg-warning">Belum Terverifikasi</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm" onclick="openDetailModal({{ $calon->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $calon->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($calon->status_verifikasi != 'terverifikasi')
                                <button class="btn btn-success btn-sm"
                                    onclick="openVerifikasiModal({{ $calon->id }}, '{{ $calon->nama_lengkap }}')">
                                    <i class="fas fa-check"></i>
                                </button>
                                @else
                                <button class="btn btn-secondary btn-sm"
                                    onclick="openBatalkanVerifikasiModal({{ $calon->id }}, '{{ $calon->nama_lengkap }}')">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $calonMahasiswa->links() }}
        </div>
    </div>
</div>

<!-- Modals Section -->
@include('calon-mahasiswa.components.modals')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.openDetailModal = async function(id) {
    try {
        const res = await fetch(`/admin/calon-mahasiswa/${id}/data`);
        const r = await res.json();
        
        if(r.success) {
            const c = r.data;
            
            document.getElementById('detail_nama').innerText = c.nama_lengkap;
            document.getElementById('detail_email').innerText = c.user?.email ?? '-';
            document.getElementById('detail_jenis_kelamin').innerText = c.jenis_kelamin;
            document.getElementById('detail_tanggal_lahir').innerText = new Date(c.tanggal_lahir).toLocaleDateString('id-ID');
            document.getElementById('detail_usia').innerText = calculateAge(c.tanggal_lahir) + ' tahun';
            document.getElementById('detail_alamat').innerText = c.alamat;
            document.getElementById('detail_no_telepon').innerText = c.no_telepon;
            document.getElementById('detail_prodi').innerText = c.program_studi?.nama_program_studi ?? '-';
            document.getElementById('detail_asal').innerText = c.asal_sekolah;
            document.getElementById('detail_tanggal').innerText = new Date(c.created_at).toLocaleString('id-ID');
            
            const fotoElement = document.getElementById('detail_foto');
            if (c.foto) {
                fotoElement.src = '/storage/' + c.foto;
            } else {
                fotoElement.src = '/images/default-profile.png';
            }
            
            const statusEl = document.getElementById('detail_status');
            statusEl.innerHTML = ''; 
            
            let badgeClass = 'badge bg-warning';
            let statusText = 'Belum Terverifikasi';
            
            if(c.status_verifikasi === 'terverifikasi') {
                badgeClass = 'badge bg-success';
                statusText = 'Terverifikasi';
            } else if(c.status_verifikasi === 'ditolak') {
                badgeClass = 'badge bg-danger';
                statusText = 'Ditolak';
            }
            
            const badge = document.createElement('span');
            badge.className = badgeClass;
            badge.innerText = statusText;
            statusEl.appendChild(badge);
            
            const modal = new bootstrap.Modal(document.getElementById('detailCalonModal'));
            modal.show();
        } else {
            alert('Gagal mengambil data calon mahasiswa');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data');
    }
}

function calculateAge(birthDate) {
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    
    return age;
}

    window.openEditModal = async function(id) {
        try {
            const res = await fetch(`/admin/calon-mahasiswa/${id}/data`);
            const r = await res.json();
            
            if(r.success) {
                const c = r.data;
                
                document.getElementById('editCalonForm').action = `/admin/calon-mahasiswa/${id}`;
                document.getElementById('edit_id').value = c.id;
                document.getElementById('edit_nama').value = c.nama_lengkap;
                document.getElementById('edit_email').value = c.user?.email ?? '';
                document.getElementById('edit_jenis_kelamin').value = c.jenis_kelamin;
                document.getElementById('edit_tanggal_lahir').value = c.tanggal_lahir;
                document.getElementById('edit_alamat').value = c.alamat;
                document.getElementById('edit_no_telepon').value = c.no_telepon;
                document.getElementById('edit_program_studi').value = c.kode_program_studi;
                document.getElementById('edit_asal').value = c.asal_sekolah;
                
                const fotoPreview = document.getElementById('edit_foto_preview');
                if (c.foto) {
                    fotoPreview.src = '/storage/' + c.foto;
                } else {
                    fotoPreview.src = '/images/default-profile.png';
                }
                
                const modal = new bootstrap.Modal(document.getElementById('editCalonModal'));
                modal.show();
            } else {
                alert('Gagal mengambil data calon mahasiswa');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data');
        }
    }

    window.openVerifikasiModal = function(id, nama) {
        document.getElementById('verif_nama').innerText = nama;
        document.getElementById('verifikasiCalonForm').action = `/admin/calon-mahasiswa/${id}/verifikasi`;
        
        const modal = new bootstrap.Modal(document.getElementById('verifikasiCalonModal'));
        modal.show();
    }

    window.openBatalkanVerifikasiModal = function(id, nama) {
        document.getElementById('batalkan_verif_nama').innerText = nama;
        document.getElementById('batalkanVerifikasiForm').action = `/admin/calon-mahasiswa/${id}/batalkan-verifikasi`;
        
        const modal = new bootstrap.Modal(document.getElementById('batalkanVerifikasiModal'));
        modal.show();
    }

    document.getElementById('edit_foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('edit_foto_preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    $(document).ready(function() {
        $('#calonMahasiswaTable').DataTable({
            searching: false,
            ordering: false,
            info: false,
            paging: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endsection