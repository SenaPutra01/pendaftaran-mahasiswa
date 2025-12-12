@extends('layouts.app')

@section('content')
<div class="step-progress">
    <div class="step completed">
        <div class="step-number">1</div>
        <div class="step-label">Register</div>
    </div>
    <div class="step completed">
        <div class="step-number">2</div>
        <div class="step-label">Data Diri</div>
    </div>
    <div class="step completed">
        <div class="step-number">3</div>
        <div class="step-label">Pilih Prodi</div>
    </div>
    <div class="step active">
        <div class="step-number">4</div>
        <div class="step-label">Pembayaran</div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>Pembayaran Biaya Pendaftaran
                </h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Silakan selesaikan pembayaran biaya pendaftaran sebesar <strong>Rp 250.000</strong>.
                    Pembayaran akan kadaluarsa dalam <strong>24 jam</strong>.
                </div>

                <div id="snap-container" class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Memuat gateway pembayaran...</p>
                </div>

                <div class="text-center mt-3">
                    <button id="refresh-btn" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sync-alt me-1"></i>Refresh Payment Gateway
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Ringkasan</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Biaya Pendaftaran:</span>
                    <strong>Rp 250.000</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Admin Fee:</span>
                    <strong>Rp 0</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="h5">Total:</span>
                    <strong class="h5 text-primary">Rp 250.000</strong>
                </div>

                <div class="alert alert-secondary">
                    <small>
                        <i class="fas fa-clock me-1"></i>
                        <strong>Batas Waktu:</strong>
                        {{ now()->addHours(24)->format('d M Y H:i') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Data Pendaftar</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Nama:</strong> {{ $calonMahasiswa->nama_lengkap }}</p>
                <p class="mb-1"><strong>Prodi:</strong> {{ $calonMahasiswa->programStudi->nama_program_studi }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p class="mb-0"><strong>No. Telepon:</strong> {{ $calonMahasiswa->no_telepon }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
<script>
    const snapToken = '{{ $snapToken }}';

window.snap.pay(snapToken, {
    onSuccess: function(result) {
        console.log('Payment success:', result);
        window.location.href = '{{ route("pendaftaran.status") }}?status=success';
    },
    onPending: function(result) {
        console.log('Payment pending:', result);
        window.location.href = '{{ route("pendaftaran.status") }}?status=pending';
    },
    onError: function(result) {
        console.log('Payment error:', result);
        window.location.href = '{{ route("pendaftaran.status") }}?status=error';
    },
    onClose: function() {
        console.log('Payment popup closed');
    }
});

document.getElementById('refresh-btn').addEventListener('click', function() {
    if (confirm('Refresh payment gateway? Token yang lama akan expired.')) {
        fetch('{{ route("pendaftaran.refresh-token") }}')
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    location.reload();
                } else {
                    alert('Gagal refresh token: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat refresh token');
            });
    }
});
</script>
@endsection