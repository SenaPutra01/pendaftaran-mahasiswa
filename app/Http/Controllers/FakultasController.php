<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fakultas = Fakultas::withCount('programStudi')->orderBy('nama_fakultas')->get();
        return view('fakultas.index', compact('fakultas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fakultas.create');
    }

    /**
     * Store a newly created resource in storage.
     */










































    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_fakultas' => 'required|string|max:100|unique:fakultas,nama_fakultas',
            'deskripsi' => 'nullable|string|max:500'
        ], [
            'nama_fakultas.required' => 'Nama fakultas wajib diisi.',
            'nama_fakultas.unique' => 'Nama fakultas sudah digunakan.',
            'nama_fakultas.max' => 'Nama fakultas maksimal 100 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        try {

            $prefix = strtoupper(substr($request->nama_fakultas, 0, 2));
            $lastFakultas = Fakultas::where('kode_fakultas', 'like', $prefix . '%')
                ->orderBy('kode_fakultas', 'desc')
                ->first();

            if ($lastFakultas) {
                $lastNumber = intval(substr($lastFakultas->kode_fakultas, 3));
                $number = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $number = '001';
            }

            $kodeFakultas = $prefix . '-' . $number;


            Fakultas::create([
                'kode_fakultas' => $kodeFakultas,
                'nama_fakultas' => $request->nama_fakultas,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('admin.fakultas.index')
                ->with('success', 'Fakultas berhasil ditambahkan. Kode: ' . $kodeFakultas);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($kode_fakultas)
    {
        $fakultas = Fakultas::with(['programStudi' => function ($query) {
            $query->orderBy('nama_program_studi');
        }])->findOrFail($kode_fakultas);

        return view('admin.fakultas.show', compact('fakultas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_fakultas)
    {
        $fakultas = Fakultas::findOrFail($kode_fakultas);
        return view('admin.fakultas.edit', compact('fakultas'));
    }

    /**
     * Update the specified resource in storage.
     */

















































    public function update(Request $request, $kode_fakultas)
    {

        $fakultas = Fakultas::findOrFail($kode_fakultas);


        $validator = Validator::make($request->all(), [
            'nama_fakultas' => [
                'required',
                'string',
                'max:100',
                'unique:fakultas,nama_fakultas,' . $fakultas->kode_fakultas . ',kode_fakultas'
            ],
            'deskripsi' => 'nullable|string|max:500'
        ], [
            'nama_fakultas.required' => 'Nama fakultas wajib diisi.',
            'nama_fakultas.unique' => 'Nama fakultas sudah digunakan.',
            'nama_fakultas.max' => 'Nama fakultas maksimal 100 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        try {

            $fakultas->update([
                'nama_fakultas' => $request->nama_fakultas,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('admin.fakultas.index')
                ->with('success', 'Fakultas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_fakultas)
    {
        $fakultas = Fakultas::findOrFail($kode_fakultas);


        if ($fakultas->programStudi()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus fakultas karena masih memiliki program studi.');
        }

        try {
            $fakultas->delete();

            return redirect()->route('admin.fakultas.index')
                ->with('success', 'Fakultas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API Endpoint untuk mendapatkan data fakultas
     */
    public function apiIndex()
    {
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();

        return response()->json([
            'success' => true,
            'data' => $fakultas
        ]);
    }

    /**
     * API Endpoint untuk mendapatkan detail fakultas
     */
    public function apiShow($kode_fakultas)
    {
        $fakultas = Fakultas::with('programStudi')->find($kode_fakultas);

        if (!$fakultas) {
            return response()->json([
                'success' => false,
                'message' => 'Fakultas tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $fakultas
        ]);
    }

    /**
     * Get program studi by fakultas (API)
     */
    public function getProgramStudiByFakultas($kode_fakultas)
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
        $totalFakultas = Fakultas::count();
        $totalProgramStudi = ProgramStudi::count();
        $fakultasWithProgramStudi = Fakultas::withCount('programStudi')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_fakultas' => $totalFakultas,
                'total_program_studi' => $totalProgramStudi,
                'fakultas' => $fakultasWithProgramStudi
            ]
        ]);
    }
}
