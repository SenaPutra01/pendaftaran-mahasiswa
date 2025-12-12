@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h5 mb-0 text-gray-800">
        <i class="fas fa-graduation-cap me-2"></i>Manajemen Program Studi
    </h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProgramStudiModal">
        <i class="fas fa-plus me-1"></i> Tambah Program Studi
    </button>
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

<div class="card shadow">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-list me-2"></i>Daftar Program Studi
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="programStudiTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Program Studi</th>
                        <th>Fakultas</th>
                        <th>Jenjang</th>
                        <th>Biaya Pendaftaran</th>
                        <th>Jumlah Calon Mahasiswa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programStudi as $prodi)
                    <tr>
                        <td>{{ $prodi->kode_program_studi }}</td>
                        <td>{{ $prodi->nama_program_studi }}</td>
                        <td>{{ $prodi->fakultas->nama_fakultas }}</td>
                        <td>
                            <span class="badge bg-info">{{ $prodi->jenjang }}</span>
                        </td>
                        <td>Rp {{ number_format($prodi->biaya_pendaftaran, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $prodi->calon_mahasiswa_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">Aktif</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-warning btn-sm"
                                    onclick="openEditModal('{{ $prodi->kode_program_studi }}')" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.program-studi.delete', $prodi->kode_program_studi) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus program studi ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('program-studi.modals.create')
@include('program-studi.modals.edit')

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    $('#programStudiTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: {
                first: "<",
                last: ">",
                next: ">",
                previous: "<"
            }
        },
        order: [[2, 'asc'], [1, 'asc']]
    });
});

async function openEditModal(kodeProgramStudi) {
    try {
        const response = await fetch(`/admin/program-studi/${kodeProgramStudi}/data`);
        const result = await response.json();
        
        if (result.success) {
            const prodi = result.data;
            
            document.getElementById('editProgramStudiForm').action = `/admin/program-studi/${prodi.kode_program_studi}`;
            document.getElementById('edit_kode_program_studi').value = prodi.kode_program_studi;
            document.getElementById('edit_nama_program_studi').value = prodi.nama_program_studi;
            document.getElementById('edit_kode_fakultas').value = prodi.kode_fakultas;
            document.getElementById('edit_jenjang').value = prodi.jenjang;
            document.getElementById('edit_biaya_pendaftaran').value = prodi.biaya_pendaftaran;
            document.getElementById('edit_deskripsi').value = prodi.deskripsi || '';
            document.getElementById('edit_status').value = prodi.status || 'active';
            
            const editModal = new bootstrap.Modal(document.getElementById('editProgramStudiModal'));
            editModal.show();
        } else {
            alert('Program studi tidak ditemukan');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data program studi');
    }
}

document.getElementById('createProgramStudiModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('createProgramStudiForm').reset();
});

document.getElementById('editProgramStudiModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('editProgramStudiForm').reset();
});

document.getElementById('create_biaya_pendaftaran').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('edit_biaya_pendaftaran').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endsection