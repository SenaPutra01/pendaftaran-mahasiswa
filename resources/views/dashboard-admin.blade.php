{{-- @extends('layouts.app')

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard Administrator</h1>

    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold">Total Calon Mahasiswa</h2>
            <p class="text-3xl text-blue-600">{{ $totalCalon }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold">Program Studi</h2>
            <p class="text-3xl text-green-600">{{ $totalProdi }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold">Total Pembayaran</h2>
            <p class="text-3xl text-purple-600">{{ $totalPembayaran }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold">Pembayaran Pending</h2>
            <p class="text-3xl text-yellow-600">{{ $pembayaranPending }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold">Pembayaran Verified</h2>
            <p class="text-3xl text-green-600">{{ $pembayaranVerified }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-3">Calon Mahasiswa Terbaru</h2>
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Prodi</th>
                    <th class="p-2 border">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calonTerbaru as $c)
                <tr>
                    <td class="p-2 border">{{ $c->nama_lengkap }}</td>
                    <td class="p-2 border">{{ $c->programStudi->nama_program_studi ?? '-' }}</td>
                    <td class="p-2 border">{{ $c->created_at->format('d-m-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-3">Pembayaran Terbaru</h2>
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Calon Mahasiswa</th>
                    <th class="p-2 border">Jumlah</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaranTerbaru as $p)
                <tr>
                    <td class="p-2 border">{{ $p->calonMahasiswa->nama_lengkap ?? '-' }}</td>
                    <td class="p-2 border">Rp {{ number_format($p->jumlah,0,',','.') }}</td>
                    <td class="p-2 border">{{ ucfirst($p->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection --}}


@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrator
        </h1>
        <div>
            <span class="badge bg-primary">
                <i class="fas fa-user-shield me-1"></i>Administrator
            </span>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row mb-4">
        <!-- Total Calon Mahasiswa -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Calon Mahasiswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCalon) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Program Studi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Program Studi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProdi) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pembayaran -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Pembayaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPembayaran, 0,
                                ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran Pending -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pembayaran Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pembayaranPending) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Chart Pendaftar per Bulan -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Pendaftar Bulan Ini</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="pendaftarChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Status Pembayaran -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Status Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="pembayaranChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Sukses
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Pending
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Gagal
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
        <!-- Calon Mahasiswa Terbaru -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Calon Mahasiswa Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <th>Program Studi</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calonTerbaru as $calon)
                                <tr>
                                    <td>{{ $calon->nama_lengkap }}</td>
                                    <td>{{ $calon->programStudi->nama_program_studi ?? '-' }}</td>
                                    <td>{{ $calon->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $calon->status_verifikasi == 'terverifikasi' ? 'success' : 
                                                            ($calon->status_verifikasi == 'menunggu_verifikasi' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $calon->status_verifikasi)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.calon-mahasiswa.show', $calon->id) }}"
                                            class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.calon-mahasiswa.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-list me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran Terbaru -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pembayaran Terbaru</h6>
                </div>
                <div class="card-body">
                    @foreach($pembayaranTerbaru as $pembayaran)
                    <div class="card mb-3 border-left-{{ $pembayaran->status == 'settlement' ? 'success' : 
                                                    ($pembayaran->status == 'pending' ? 'warning' : 'danger') }}">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $pembayaran->calonMahasiswa->nama_lengkap ?? 'N/A' }}</h6>
                                    <p class="mb-0 text-muted small">
                                        {{ $pembayaran->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $pembayaran->status == 'settlement' ? 'success' : 
                                                        ($pembayaran->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($pembayaran->status) }}
                                    </span>
                                    <div class="mt-1 text-dark">
                                        <strong>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($pembayaranTerbaru->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-receipt fa-2x mb-2"></i>
                        <p>Belum ada data pembayaran</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-primary">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div class="h5 mt-2">{{ $pembayaranVerified }}</div>
                            <small>Pembayaran Verified</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-warning">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div class="h5 mt-2">{{ $pembayaranPending }}</div>
                            <small>Pembayaran Pending</small>
                        </div>
                        <div class="col-6">
                            <div class="text-success">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                            <div class="h5 mt-2">{{ $totalCalon }}</div>
                            <small>Total Calon</small>
                        </div>
                        <div class="col-6">
                            <div class="text-info">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                            <div class="h5 mt-2">{{ $totalProdi }}</div>
                            <small>Program Studi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Chart Pendaftar per Bulan
const pendaftarCtx = document.getElementById('pendaftarChart').getContext('2d');
const pendaftarChart = new Chart(pendaftarCtx, {
    type: 'bar',
    data: {
        labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
        datasets: [{
            label: 'Jumlah Pendaftar',
            data: [12, 19, 8, 15],
            backgroundColor: 'rgba(78, 115, 223, 0.8)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 1
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Chart Pembayaran
const pembayaranCtx = document.getElementById('pembayaranChart').getContext('2d');
const pembayaranChart = new Chart(pembayaranCtx, {
    type: 'doughnut',
    data: {
        labels: ['Sukses', 'Pending', 'Gagal'],
        datasets: [{
            data: [{{ $pembayaranVerified }}, {{ $pembayaranPending }}, {{ $totalPembayaran - $pembayaranVerified - $pembayaranPending }}],
            backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
            hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        cutout: '70%'
    }
});
</script>

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

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #6e707e;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .chart-area {
        position: relative;
        height: 10rem;
        width: 100%;
    }

    @media (min-width: 768px) {
        .chart-area {
            height: 20rem;
        }
    }
</style>
@endsection