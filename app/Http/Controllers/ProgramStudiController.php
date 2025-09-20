<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProgramStudiController extends Controller
{
    public function getProgramStudiData($kode_program_studi)
    {
        $programStudi = ProgramStudi::find($kode_program_studi);

        if (!$programStudi) {
            return response()->json([
                'success' => false,
                'message' => 'Program studi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $programStudi
        ]);
    }

    /**
     * Display a listing of the resource dengan data untuk modal
     */
    public function index()
    {
        $programStudi = ProgramStudi::with(['fakultas', 'calonMahasiswa'])
            ->withCount('calonMahasiswa')
            ->orderBy('kode_fakultas')
            ->orderBy('nama_program_studi')
            ->get();

        $fakultas = Fakultas::orderBy('nama_fakultas')->get();

        return view('program-studi.index', compact('programStudi', 'fakultas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();
        $selectedFakultas = $request->query('fakultas');

        return view('admin.program-studi.create', compact('fakultas', 'selectedFakultas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_program_studi' => [
                'required',
                'string',
                'max:10',
                'unique:program_studi,kode_program_studi',
                'regex:/^[A-Z0-9]+$/'
            ],
            'nama_program_studi' => 'required|string|max:100|unique:program_studi,nama_program_studi',
            'kode_fakultas' => 'required|exists:fakultas,kode_fakultas',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:500'
        ], [
            'kode_program_studi.required' => 'Kode program studi wajib diisi.',
            'kode_program_studi.unique' => 'Kode program studi sudah digunakan.',
            'kode_program_studi.regex' => 'Kode program studi hanya boleh mengandung huruf kapital dan angka.',
            'kode_program_studi.max' => 'Kode program studi maksimal 10 karakter.',
            'nama_program_studi.required' => 'Nama program studi wajib diisi.',
            'nama_program_studi.unique' => 'Nama program studi sudah digunakan.',
            'nama_program_studi.max' => 'Nama program studi maksimal 100 karakter.',
            'kode_fakultas.required' => 'Fakultas wajib dipilih.',
            'kode_fakultas.exists' => 'Fakultas yang dipilih tidak valid.',
            'jenjang.required' => 'Jenjang wajib dipilih.',
            'jenjang.in' => 'Jenjang yang dipilih tidak valid.',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran wajib diisi.',
            'biaya_pendaftaran.numeric' => 'Biaya pendaftaran harus berupa angka.',
            'biaya_pendaftaran.min' => 'Biaya pendaftaran minimal 0.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        try {
            ProgramStudi::create($request->all());

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program studi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($kode_program_studi)
    {
        $programStudi = ProgramStudi::with(['fakultas', 'calonMahasiswa'])
            ->findOrFail($kode_program_studi);

        return view('admin.program-studi.show', compact('programStudi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_program_studi)
    {
        $programStudi = ProgramStudi::findOrFail($kode_program_studi);
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();

        return view('admin.program-studi.edit', compact('programStudi', 'fakultas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_program_studi)
    {
        $programStudi = ProgramStudi::findOrFail($kode_program_studi);

        $validator = Validator::make($request->all(), [
            'kode_program_studi' => [
                'required',
                'string',
                'max:10',
                'unique:program_studi,kode_program_studi,' . $programStudi->kode_program_studi . ',kode_program_studi',
                'regex:/^[A-Z0-9]+$/'
            ],
            'nama_program_studi' => [
                'required',
                'string',
                'max:100',
                'unique:program_studi,nama_program_studi,' . $programStudi->kode_program_studi . ',kode_program_studi'
            ],
            'kode_fakultas' => 'required|exists:fakultas,kode_fakultas',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:500'
        ], [
            'kode_program_studi.required' => 'Kode program studi wajib diisi.',
            'kode_program_studi.unique' => 'Kode program studi sudah digunakan.',
            'kode_program_studi.regex' => 'Kode program studi hanya boleh mengandung huruf kapital dan angka.',
            'kode_program_studi.max' => 'Kode program studi maksimal 10 karakter.',
            'nama_program_studi.required' => 'Nama program studi wajib diisi.',
            'nama_program_studi.unique' => 'Nama program studi sudah digunakan.',
            'nama_program_studi.max' => 'Nama program studi maksimal 100 karakter.',
            'kode_fakultas.required' => 'Fakultas wajib dipilih.',
            'kode_fakultas.exists' => 'Fakultas yang dipilih tidak valid.',
            'jenjang.required' => 'Jenjang wajib dipilih.',
            'jenjang.in' => 'Jenjang yang dipilih tidak valid.',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran wajib diisi.',
            'biaya_pendaftaran.numeric' => 'Biaya pendaftaran harus berupa angka.',
            'biaya_pendaftaran.min' => 'Biaya pendaftaran minimal 0.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        try {
            $programStudi->update($request->all());

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program studi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_program_studi)
    {
        $programStudi = ProgramStudi::findOrFail($kode_program_studi);

        // Cek apakah program studi memiliki calon mahasiswa
        if ($programStudi->calonMahasiswa()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus program studi karena masih memiliki calon mahasiswa.');
        }

        try {
            $programStudi->delete();

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program studi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API Endpoint untuk mendapatkan data program studi
     */
    public function apiIndex()
    {
        $programStudi = ProgramStudi::with('fakultas')
            ->orderBy('nama_program_studi')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $programStudi
        ]);
    }

    /**
     * API Endpoint untuk mendapatkan detail program studi
     */
    public function apiShow($kode_program_studi)
    {
        $programStudi = ProgramStudi::with('fakultas')->find($kode_program_studi);

        if (!$programStudi) {
            return response()->json([
                'success' => false,
                'message' => 'Program studi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $programStudi
        ]);
    }

    /**
     * API Endpoint untuk mendapatkan program studi berdasarkan fakultas
     */
    public function apiByFakultas($kode_fakultas)
    {
        $programStudi = ProgramStudi::where('kode_fakultas', $kode_fakultas)
            ->orderBy('nama_program_studi')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $programStudi
        ]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        $totalProgramStudi = ProgramStudi::count();
        $programStudiByJenjang = ProgramStudi::select('jenjang', DB::raw('count(*) as total'))
            ->groupBy('jenjang')
            ->get();
        $programStudiByFakultas = ProgramStudi::with('fakultas')
            ->select('kode_fakultas', DB::raw('count(*) as total'))
            ->groupBy('kode_fakultas')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_program_studi' => $totalProgramStudi,
                'by_jenjang' => $programStudiByJenjang,
                'by_fakultas' => $programStudiByFakultas
            ]
        ]);
    }
}
