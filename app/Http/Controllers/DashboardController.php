<?php

namespace App\Http\Controllers;

use App\Models\CalonMahasiswa;
use App\Models\Pembayaran;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Administrator
     */
    public function adminDashboard()
    {

        $totalCalon = CalonMahasiswa::count();
        $totalProdi = ProgramStudi::count();


        $totalPembayaran = Pembayaran::count();
        $pembayaranPending = Pembayaran::where('status', 'pending')->count();
        $pembayaranVerified = Pembayaran::where('status', 'settlement')->count();


        $calonTerbaru = CalonMahasiswa::with('programStudi')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $pembayaranTerbaru = Pembayaran::with('calonMahasiswa')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard-admin', compact(
            'totalCalon',
            'totalProdi',
            'totalPembayaran',
            'pembayaranPending',
            'pembayaranVerified',
            'calonTerbaru',
            'pembayaranTerbaru'
        ));
    }

    /**
     * Dashboard Mahasiswa
     */
    public function mahasiswaDashboard()
    {
        $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())
            ->with(['programStudi.fakultas', 'pembayaran'])
            ->first();

        if (!$calonMahasiswa) {
            return redirect()->route('pendaftaran.register')
                ->with('info', 'Silakan lengkapi pendaftaran terlebih dahulu.');
        }
        $pembayaran = $calonMahasiswa->pembayaran;

        return view('dashboard-mahasiswa', compact('calonMahasiswa', 'pembayaran'));
    }
}
