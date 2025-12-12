<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CalonMahasiswaController extends Controller
{
    /**
     * Menampilkan daftar calon mahasiswa
     */
    public function index(Request $request)
    {

        $query = CalonMahasiswa::with(['user', 'programStudi.fakultas']);


        if ($request->has('program_studi') && !empty($request->program_studi)) {
            $query->where('kode_program_studi', $request->program_studi);
        }


        if ($request->has('tanggal_mulai') && !empty($request->tanggal_mulai)) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && !empty($request->tanggal_selesai)) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }


        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('asal_sekolah', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('no_telepon', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('email', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }


        $calonMahasiswa = $query->orderBy('created_at', 'desc')->paginate(10);


        $totalPendaftar = CalonMahasiswa::count();
        $pendaftarHariIni = CalonMahasiswa::whereDate('created_at', today())->count();
        $pendaftarBulanIni = CalonMahasiswa::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();


        $programStudi = ProgramStudi::with('fakultas')->get();

        return view('calon-mahasiswa.index', compact(
            'calonMahasiswa',
            'totalPendaftar',
            'pendaftarHariIni',
            'pendaftarBulanIni',
            'programStudi'
        ));
    }

    /**
     * Mengambil data calon mahasiswa untuk modal (API)
     */
    public function getData($id)
    {
        try {
            $calon = CalonMahasiswa::with(['user', 'programStudi'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $calon
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Menampilkan detail calon mahasiswa
     */
    public function show($id)
    {
        $calon = CalonMahasiswa::with(['user', 'programStudi.fakultas'])->findOrFail($id);
        return view('calon-mahasiswa.show', compact('calon'));
    }

    /**
     * Menampilkan form edit calon mahasiswa
     */
    public function edit($id)
    {
        $calon = CalonMahasiswa::with(['user', 'programStudi'])->findOrFail($id);
        $programStudi = ProgramStudi::all();

        return view('calon-mahasiswa.edit', compact('calon', 'programStudi'));
    }

    /**
     * Memperbarui data calon mahasiswa
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'kode_program_studi' => 'required|exists:program_studi,kode_program_studi',
            'asal_sekolah' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $calon = CalonMahasiswa::findOrFail($id);


            $fotoPath = $calon->foto;
            if ($request->hasFile('foto')) {

                if ($calon->foto && Storage::exists($calon->foto)) {
                    Storage::delete($calon->foto);
                }


                $fotoPath = $request->file('foto')->store('foto-calon-mahasiswa', 'public');
            }

            $calon->update([
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'kode_program_studi' => $request->kode_program_studi,
                'asal_sekolah' => $request->asal_sekolah,
                'foto' => $fotoPath,
            ]);


            if ($calon->user) {
                $calon->user->update([
                    'email' => $request->email
                ]);
            }

            DB::commit();

            return redirect()->route('admin.calon-mahasiswa.index')
                ->with('success', 'Data calon mahasiswa berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Memverifikasi calon mahasiswa
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        try {
            $calon = CalonMahasiswa::findOrFail($id);
            $calon->update([
                'status_verifikasi' => 'terverifikasi',
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            return redirect()->route('admin.calon-mahasiswa.index')
                ->with('success', 'Calon mahasiswa berhasil diverifikasi');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membatalkan verifikasi calon mahasiswa
     */
    public function batalkanVerifikasi(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'nullable|string|max:500',
        ]);

        try {
            $calon = CalonMahasiswa::findOrFail($id);
            $calon->update([
                'status_verifikasi' => 'belum_verifikasi',
                'tanggal_verifikasi' => null,
                'catatan_verifikasi' => $request->alasan_pembatalan,
            ]);

            return redirect()->route('admin.calon-mahasiswa.index')
                ->with('success', 'Verifikasi calon mahasiswa berhasil dibatalkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus calon mahasiswa
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $calon = CalonMahasiswa::findOrFail($id);


            if ($calon->foto && Storage::exists($calon->foto)) {
                Storage::delete($calon->foto);
            }


            if ($calon->user) {
                $calon->user->delete();
            }

            $calon->delete();

            DB::commit();

            return redirect()->route('admin.calon-mahasiswa.index')
                ->with('success', 'Data calon mahasiswa berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export data calon mahasiswa
     */
    public function export(Request $request)
    {

        $query = CalonMahasiswa::with(['user', 'programStudi.fakultas']);


        if ($request->has('program_studi') && !empty($request->program_studi)) {
            $query->where('kode_program_studi', $request->program_studi);
        }

        if ($request->has('tanggal_mulai') && !empty($request->tanggal_mulai)) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && !empty($request->tanggal_selesai)) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        $data = $query->orderBy('created_at', 'desc')->get();


        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="calon-mahasiswa-' . date('Y-m-d') . '.csv"',
        ];


        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');


            fputcsv($file, [
                'Nama Lengkap',
                'Email',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Alamat',
                'No. Telepon',
                'Program Studi',
                'Fakultas',
                'Asal Sekolah',
                'Tanggal Daftar',
                'Status Verifikasi'
            ]);


            foreach ($data as $row) {
                fputcsv($file, [
                    $row->nama_lengkap,
                    $row->user->email ?? '-',
                    $row->jenis_kelamin,
                    $row->tanggal_lahir->format('d/m/Y'),
                    $row->alamat,
                    $row->no_telepon,
                    $row->programStudi->nama_program_studi ?? '-',
                    $row->programStudi->fakultas->nama_fakultas ?? '-',
                    $row->asal_sekolah,
                    $row->created_at->format('d/m/Y H:i'),
                    $row->status_verifikasi
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
