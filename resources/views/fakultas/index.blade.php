{{-- @extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-building me-2"></i>Manajemen Fakultas
    </h1>
    <a href="{{ route('admin.fakultas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Fakultas
    </a>
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
            <i class="fas fa-list me-2"></i>Daftar Fakultas
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="fakultasTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Fakultas</th>
                        <th>Jumlah Program Studi</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fakultas as $fak)
                    <tr>
                        <td>{{ $fak->kode_fakultas }}</td>
                        <td>{{ $fak->nama_fakultas }}</td>
                        <td>
                            <span class="badge bg-info">{{ $fak->program_studi_count }}</span>
                        </td>
                        <td>{{ $fak->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.fakultas.show', $fak->kode_fakultas) }}"
                                    class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.fakultas.edit', $fak->kode_fakultas) }}"
                                    class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.fakultas.delete', $fak->kode_fakultas) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus fakultas ini?')">
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    $('#fakultasTable').DataTable({
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
        },
        order: [[1, 'asc']]
    });
});
</script>
@endsection --}}


@extends('layouts.app')

@section('content')
<div class="col-xxl-12 col-lg-6">
    <div class="card h-100">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                <h6 class="mb-2 fw-bold text-lg mb-0">List Fakultas</h6>
                <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 py-2" data-bs-toggle="modal"
                    data-bs-target="#createFakultasModal">
                    <i class="fas fa-plus"></i>
                    Add New
                </button>
            </div>

            <div class="table-responsive scroll-sm">
                <table class="table table-bordered table-hover mb-0" id="fakultasTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Fakultas</th>
                            <th>Jumlah Program Studi</th>
                            <th>Dibuat</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fakultas as $fak)
                        <tr>
                            <td>{{ $fak->kode_fakultas }}</td>
                            <td>{{ $fak->nama_fakultas }}</td>
                            <td><span class="badge bg-info">{{ $fak->program_studi_count }}</span></td>
                            <td>{{ $fak->created_at->format('d-m-Y') }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-warning"
                                    onclick="openEditFakultasModal('{{ $fak->kode_fakultas }}')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.fakultas.delete', $fak->kode_fakultas) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@include('fakultas.components.fakultas-create-modal')
@include('fakultas.components.fakultas-edit-modal')
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

    window.openEditFakultasModal = async function(kodeFakultas) {
        try {
            const response = await fetch(`/admin/api/fakultas/${kodeFakultas}`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();

            if(result.success) {
                const fak = result.data;
                const form = document.getElementById('editFakultasForm');
                form.action = `/admin/fakultas/${fak.kode_fakultas}`;

                document.getElementById('edit_kode_fakultas').value = fak.kode_fakultas;
                document.getElementById('edit_nama_fakultas').value = fak.nama_fakultas;

                new bootstrap.Modal(document.getElementById('editFakultasModal')).show();
            } else {
                alert('Data Fakultas tidak ditemukan!');
            }
        } catch(e) {
            console.error(e);
            alert('Terjadi kesalahan saat memuat data Fakultas');
        }
    }

    $('#fakultasTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: 4 },
            { className: 'text-center', targets: 4 }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
        }
    });
});
</script>
@endsection