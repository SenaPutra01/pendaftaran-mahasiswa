<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CalonMahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('calonMahasiswa')->get();
        $programStudi = ProgramStudi::all();

        return view('users.index', compact('users', 'programStudi'));
    }

    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:administrator,mahasiswa',
            'kode_program_studi' => 'required_if:role,mahasiswa|exists:program_studi,kode_program_studi',
            'jenis_kelamin' => 'required_if:role,mahasiswa|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required_if:role,mahasiswa|date',
            'alamat' => 'required_if:role,mahasiswa|string|max:500',
            'no_telepon' => 'required_if:role,mahasiswa|string|max:15',
            'asal_sekolah' => 'required_if:role,mahasiswa|string|max:100',
        ];



        $messages = [];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        try {
            DB::beginTransaction();


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);


            if ($request->role == 'mahasiswa') {
                CalonMahasiswa::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $request->name,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                    'asal_sekolah' => $request->asal_sekolah,
                    'kode_program_studi' => $request->kode_program_studi,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getUserData($id)
    {
        $user = User::with('calonMahasiswa')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:administrator,mahasiswa',
            'kode_program_studi' => 'required_if:role,mahasiswa|exists:program_studi,kode_program_studi',
            'jenis_kelamin' => 'required_if:role,mahasiswa|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required_if:role,mahasiswa|date',
            'alamat' => 'required_if:role,mahasiswa|string|max:500',
            'no_telepon' => 'required_if:role,mahasiswa|string|max:15',
            'asal_sekolah' => 'required_if:role,mahasiswa|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        try {
            DB::beginTransaction();


            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);


            if ($request->role == 'mahasiswa') {
                $calonMahasiswaData = [
                    'nama_lengkap' => $request->name,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                    'asal_sekolah' => $request->asal_sekolah,
                    'kode_program_studi' => $request->kode_program_studi,
                ];

                if ($user->calonMahasiswa) {
                    $user->calonMahasiswa->update($calonMahasiswaData);
                } else {
                    $calonMahasiswaData['user_id'] = $user->id;
                    CalonMahasiswa::create($calonMahasiswaData);
                }
            } else {

                if ($user->calonMahasiswa) {
                    $user->calonMahasiswa->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();


            if ($user->calonMahasiswa) {
                $user->calonMahasiswa->delete();
            }


            $user->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
