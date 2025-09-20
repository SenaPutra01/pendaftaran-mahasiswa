@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="fas fa-clipboard-list me-2"></i>Status Pendaftaran
        </h4>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <img src="{{ $calonMahasiswa->foto_url }}" alt="Foto Profil" class="img-fluid rounded"
                    style="max-height: 200px;">
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title">Status Pendaftaran</h6>
                                @if($calonMahasiswa->status_verifikasi == 'terverifikasi')
                                <span class="badge bg-success status-badge">Terverifikasi</span>
                                @elseif($calonMahasiswa->status_verifikasi == 'menunggu_verifikasi')
                                <span class="badge bg-warning status-badge">Menunggu Verifikasi</span>
                                @else
                                <span class="badge bg-secondary status-badge">Belum Diverifikasi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title">Status Pembayaran</h6>
                                @if($calonMahasiswa->pembayaran && $calonMahasiswa->pembayaran->is_paid)
                                <span class="badge bg-success status-badge">Lunas</span>
                                @elseif($calonMahasiswa->pembayaran && $calonMahasiswa->pembayaran->is_pending)
                                <span class="badge bg-warning status-badge">Pending</span>
                                @else
                                <span class="badge bg-danger status-badge">Belum Bayar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Nama Lengkap</th>
                        <td>{{ $calonMahasiswa->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td>
                            {{ $calonMahasiswa->programStudi->nama_program_studi }}
                            ({{ $calonMahasiswa->programStudi->fakultas->nama_fakultas }})
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <td>{{ $calonMahasiswa->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-receipt me-2"></i>Informasi Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        @if($calonMahasiswa->pembayaran)
                        <p class="mb-1"><strong>Order ID:</strong> {{ $calonMahasiswa->pembayaran->order_id }}</p>
                        <p class="mb-1"><strong>Jumlah:</strong> Rp {{
                            number_format($calonMahasiswa->pembayaran->jumlah, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong>Status:</strong>
                            <span
                                class="badge bg-{{ $calonMahasiswa->pembayaran->is_paid ? 'success' : ($calonMahasiswa->pembayaran->is_pending ? 'warning' : 'danger') }}">
                                {{ $calonMahasiswa->pembayaran->status }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $calonMahasiswa->pembayaran->payment_type
                            ?? '-' }}</p>
                        <p class="mb-0"><strong>Waktu Kadaluarsa:</strong>
                            {{ $calonMahasiswa->pembayaran->waktu_kadaluarsa ?
                            $calonMahasiswa->pembayaran->waktu_kadaluarsa->format('d/m/Y H:i') : '-' }}
                        </p>
                        @else
                        <p class="text-center text-muted">Belum ada data pembayaran</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-list-alt me-2"></i>Next Steps</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if(!$calonMahasiswa->pembayaran || !$calonMahasiswa->pembayaran->is_paid)
                            <a href="{{ route('pendaftaran.pembayaran') }}" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i>Lanjutkan Pembayaran
                            </a>
                            @endif

                            @if($calonMahasiswa->pembayaran && $calonMahasiswa->pembayaran->is_paid)
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-check me-2"></i>Pembayaran Berhasil
                            </button>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Download Bukti Daftar
                            </a>
                            @endif

                            <a href="{{ route('pendaftaran.data-diri') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-edit me-2"></i>Edit Data Diri
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection