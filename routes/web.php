<?php

use App\Http\Controllers\CalonMahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\PendaftaranController;
// use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramStudiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     $user = Auth::user();

//     if ($user->role === 'administrator') {
//         return redirect()->route('dashboard.admin');
//     }

//     if ($user->role === 'mahasiswa') {
//         return redirect()->route('dashboard.mahasiswa');
//     }

//     abort(403, 'Unauthorized');
// })->name('dashboard');

// Route::middleware(['auth', 'role:administrator'])->group(function () {
//     Route::get('/dashboard/admin', [DashboardController::class, 'index'])
//         ->name('dashboard.admin');
// });

// // Dashboard untuk Mahasiswa
// Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
//     Route::get('/dashboard/mahasiswa', [DashboardController::class, 'index'])
//         ->name('dashboard.mahasiswa');
// });


// routes/web.php
Route::middleware(['auth'])->group(function () {



    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])
        ->name('dashboard.admin');
    // ->middleware('can:admin');

    Route::get('/dashboard/mahasiswa', [DashboardController::class, 'mahasiswaDashboard'])
        ->name('dashboard.mahasiswa');
    // ->middleware('can:mahasiswa');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'administrator') {
            return redirect()->route('dashboard.admin');
        }
        return redirect()->route('dashboard.mahasiswa');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/data', [UserController::class, 'getUserData'])->name('data');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    Route::prefix('fakultas')->name('fakultas.')->group(function () {
        Route::get('/', [FakultasController::class, 'index'])->name('index');
        Route::get('/create', [FakultasController::class, 'create'])->name('create');
        Route::post('/', [FakultasController::class, 'store'])->name('store');
        Route::get('/{kode_fakultas}', [FakultasController::class, 'show'])->name('show');
        Route::get('/{kode_fakultas}/edit', [FakultasController::class, 'edit'])->name('edit');
        Route::put('/{kode_fakultas}', [FakultasController::class, 'update'])->name('update');
        Route::delete('/{kode_fakultas}', [FakultasController::class, 'destroy'])->name('delete');

        // API Routes
        Route::get('/{kode_fakultas}/program-studi', [FakultasController::class, 'getProgramStudiByFakultas'])->name('program-studi');
    });

    // API Routes untuk fakultas
    Route::get('/api/fakultas', [FakultasController::class, 'apiIndex']);
    Route::get('/api/fakultas/{kode_fakultas}', [FakultasController::class, 'apiShow']);
    Route::get('/api/fakultas-statistics', [FakultasController::class, 'getStatistics']);


    Route::prefix('program-studi')->name('program-studi.')->group(function () {
        Route::get('/', [ProgramStudiController::class, 'index'])->name('index');
        Route::get('/create', [ProgramStudiController::class, 'create'])->name('create');
        Route::post('/', [ProgramStudiController::class, 'store'])->name('store');
        Route::get('/{kode_program_studi}', [ProgramStudiController::class, 'show'])->name('show');
        Route::get('/{kode_program_studi}/edit', [ProgramStudiController::class, 'edit'])->name('edit');
        Route::put('/{kode_program_studi}', [ProgramStudiController::class, 'update'])->name('update');
        Route::delete('/{kode_program_studi}', [ProgramStudiController::class, 'destroy'])->name('delete');

        // API Routes
        Route::get('/fakultas/{kode_fakultas}', [ProgramStudiController::class, 'apiByFakultas'])->name('by-fakultas');
        Route::get('/{kode_program_studi}/data', [ProgramStudiController::class, 'getProgramStudiData'])->name('data');
    });

    // API Routes untuk program studi
    Route::get('/api/program-studi', [ProgramStudiController::class, 'apiIndex']);
    Route::get('/api/program-studi/{kode_program_studi}', [ProgramStudiController::class, 'apiShow']);
    Route::get('/api/program-studi-statistics', [ProgramStudiController::class, 'getStatistics']);
    // Dalam group program-studi

    Route::prefix('calon-mahasiswa')->name('calon-mahasiswa.')->group(function () {
        Route::get('/', [CalonMahasiswaController::class, 'index'])->name('index');
        Route::get('/{id}', [CalonMahasiswaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CalonMahasiswaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CalonMahasiswaController::class, 'update'])->name('update');
        Route::delete('/{id}', [CalonMahasiswaController::class, 'destroy'])->name('delete');

        // Verifikasi Routes
        Route::post('/{id}/verifikasi', [CalonMahasiswaController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/{id}/batalkan-verifikasi', [CalonMahasiswaController::class, 'batalkanVerifikasi'])->name('batalkan-verifikasi');

        // Export Route
        Route::get('/export/data', [CalonMahasiswaController::class, 'export'])->name('export');


        // Route::get('calon-mahasiswa', [CalonMahasiswaController::class, 'index'])->name('index');
        Route::get('/{id}/data', [CalonMahasiswaController::class, 'getData'])->name('calon-mahasiswa.data');
        // Route::get('calon-mahasiswa/{id}', [CalonMahasiswaController::class, 'show'])->name('calon-mahasiswa.show');
        // Route::get('calon-mahasiswa/{id}/edit', [CalonMahasiswaController::class, 'edit'])->name('calon-mahasiswa.edit');
        // Route::put('calon-mahasiswa/{id}', [CalonMahasiswaController::class, 'update'])->name('calon-mahasiswa.update');
        // Route::post('calon-mahasiswa/{id}/verifikasi', [CalonMahasiswaController::class, 'verifikasi'])->name('calon-mahasiswa.verifikasi');
        // Route::post('calon-mahasiswa/{id}/batalkan-verifikasi', [CalonMahasiswaController::class, 'batalkanVerifikasi'])->name('calon-mahasiswa.batalkan-verifikasi');
        // Route::delete('calon-mahasiswa/{id}', [CalonMahasiswaController::class, 'destroy'])->name('calon-mahasiswa.delete');
        Route::get('calon-mahasiswa/export', [CalonMahasiswaController::class, 'export'])->name('calon-mahasiswa.export');
    });

    // API Routes untuk calon mahasiswa
    Route::get('/api/calon-mahasiswa', [CalonMahasiswaController::class, 'apiIndex']);
    Route::get('/api/calon-mahasiswa/{id}', [CalonMahasiswaController::class, 'apiShow']);
    Route::get('/api/calon-mahasiswa-statistics', [CalonMahasiswaController::class, 'getStatistics']);
});

// Routes untuk pendaftaran calon mahasiswa
// Routes pendaftaran
Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
    // Register akun
    Route::get('register', [PendaftaranController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [PendaftaranController::class, 'register'])->name('register.post');

    // Data diri (setelah login)
    Route::middleware(['auth', 'calon_mahasiswa'])->group(function () {
        Route::get('data-diri', [PendaftaranController::class, 'showDataDiriForm'])->name('data-diri');
        Route::post('data-diri', [PendaftaranController::class, 'simpanDataDiri'])->name('data-diri.post');

        Route::get('pilih-prodi', [PendaftaranController::class, 'showPilihProdiForm'])->name('pilih-prodi');
        Route::post('pilih-prodi', [PendaftaranController::class, 'simpanProdi'])->name('pilih-prodi.post');

        Route::get('pembayaran', [PendaftaranController::class, 'showPembayaranForm'])->name('pembayaran');
        Route::get('refresh-token', [PendaftaranController::class, 'refreshSnapToken'])->name('refresh-token');
        Route::get('status', [PendaftaranController::class, 'status'])->name('status');
    });

    // Callback dari Midtrans
    Route::post('payment-callback', [PendaftaranController::class, 'handlePaymentCallback'])->name('payment-callback');
    Route::post('webhook', [PendaftaranController::class, 'handleWebhook'])->name('webhook');
});

require __DIR__ . '/auth.php';
