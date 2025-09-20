@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard Mahasiswa
        </h1>
        <div>
            <span class="badge bg-info">
                <i class="fas fa-user-graduate me-1"></i>Calon Mahasiswa
            </span>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Selamat Datang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Halo, {{ Auth::user()->name }}!</div>
                            <p class="mt-2 mb-0 text-muted">Selamat datang di sistem pendaftaran mahasiswa baru.</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($calonMahasiswa)
    <div class="row">
        <!-- Informasi Pendaftaran -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pendaftaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Nama Lengkap:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $calonMahasiswa->nama_lengkap }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Program Studi:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="text-primary">
                                {{ $calonMahasiswa->programStudi->nama_program_studi ?? 'Belum dipilih' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Status Verifikasi:</strong>
                        </div>
                        <div class="col-sm-8">
                            @if($calonMahasiswa->status_verifikasi === 'terverifikasi')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Terverifikasi
                            </span>
                            @elseif($calonMahasiswa->status_verifikasi === 'menunggu_verifikasi')
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>Menunggu Verifikasi
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-times-circle me-1"></i>Belum Diverifikasi
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Tanggal Daftar:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $calonMahasiswa->created_at->format('d F Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Pembayaran -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-credit-card me-2"></i>Status Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    @if($pembayaran)
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Jumlah:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="font-weight-bold text-primary">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-8">
                            @if($pembayaran->status == 'pending')
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>Menunggu Pembayaran
                            </span>
                            @elseif(in_array($pembayaran->status, ['settlement','capture']))
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Lunas
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>{{ ucfirst($pembayaran->status) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Order ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            <code>{{ $pembayaran->order_id }}</code>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Tanggal:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $pembayaran->created_at->format('d F Y H:i') }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill-wave fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">Belum ada data pembayaran</p>
                        <a href="{{ route('pendaftaran.pembayaran') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-credit-card me-1"></i>Lakukan Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Pendaftaran -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks me-2"></i>Progress Pendaftaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="progress mb-4" style="height: 20px;">
                        @php
                        $progress = 0;
                        if ($calonMahasiswa->nik) $progress += 25;
                        if ($calonMahasiswa->kode_program_studi) $progress += 25;
                        if ($pembayaran && in_array($pembayaran->status, ['settlement','capture'])) $progress += 25;
                        if ($calonMahasiswa->status_verifikasi === 'terverifikasi') $progress += 25;
                        @endphp
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-3">
                            <div class="mb-2">
                                <i
                                    class="fas fa-{{ $calonMahasiswa->nik ? 'check-circle text-success' : 'circle text-secondary' }} fa-2x"></i>
                            </div>
                            <small class="d-block">Data Diri</small>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <i
                                    class="fas fa-{{ $calonMahasiswa->kode_program_studi ? 'check-circle text-success' : 'circle text-secondary' }} fa-2x"></i>
                            </div>
                            <small class="d-block">Program Studi</small>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <i
                                    class="fas fa-{{ $pembayaran && in_array($pembayaran->status, ['settlement','capture']) ? 'check-circle text-success' : 'circle text-secondary' }} fa-2x"></i>
                            </div>
                            <small class="d-block">Pembayaran</small>
                        </div>
                        <div class="col-3">
                            <div class="mb-2">
                                <i
                                    class="fas fa-{{ $calonMahasiswa->status_verifikasi === 'terverifikasi' ? 'check-circle text-success' : 'circle text-secondary' }} fa-2x"></i>
                            </div>
                            <small class="d-block">Verifikasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pendaftaran.data-diri') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-edit me-2"></i>Lengkapi Data Diri
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pendaftaran.pilih-prodi') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-book me-2"></i>Pilih Program Studi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pendaftaran.pembayaran') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-credit-card me-2"></i>Lakukan Pembayaran
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pendaftaran.status') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-info-circle me-2"></i>Lihat Status
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Belum Mendaftar -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-graduate fa-4x text-gray-300 mb-4"></i>
                    <h4 class="text-gray-800">Anda Belum Melakukan Pendaftaran</h4>
                    <p class="text-muted mb-4">Silakan lengkapi proses pendaftaran untuk mengakses dashboard lengkap</p>
                    <a href="{{ route('pendaftaran.data-diri') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Mulai Pendaftaran
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .progress-bar-striped {
        background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%,
                transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%,
                rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem;
    }

    .btn {
        border-radius: 0.35rem;
        transition: all 0.15s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endsection