<?php

namespace App\Http\Controllers;

use App\Models\CalonMahasiswa;
use App\Models\Pembayaran;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans dengan error handling
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        if (empty($serverKey) || empty($clientKey)) {
            Log::error('Midtrans configuration missing: serverKey or clientKey is empty');
            // Tidak throw exception di constructor, biarkan method handle sendiri
        }

        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Menampilkan form register akun
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('pendaftaran.data-diri');
        }

        return view('pendaftaran.register');
    }

    /**
     * Proses register akun
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'terms' => 'required|accepted',
        ], [
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam pengisian form.');
        }

        try {
            DB::beginTransaction();

            // Buat user baru
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]);

            // Buat data calon mahasiswa dasar
            CalonMahasiswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'status_verifikasi' => 'belum_verifikasi',
            ]);

            DB::commit();

            // Login otomatis
            Auth::login($user);

            return redirect()->route('pendaftaran.data-diri')
                ->with('success', 'Akun berhasil dibuat! Silakan lengkapi data diri.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form data diri
     */
    public function showDataDiriForm()
    {
        if (!Auth::check()) {
            return redirect()->route('pendaftaran.register');
        }

        $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())->first();

        if (!$calonMahasiswa) {
            return redirect()->route('pendaftaran.register');
        }

        return view('pendaftaran.data-diri', compact('calonMahasiswa'));
    }

    /**
     * Proses simpan data diri
     */
    public function simpanDataDiri(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('pendaftaran.register');
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'required|digits:16',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'asal_sekolah' => 'required|string|max:100',
            'nama_orang_tua' => 'required|string|max:100',
            'pekerjaan_orang_tua' => 'required|string|max:50',
            'penghasilan_orang_tua' => 'required|string|max:50',
            'no_telepon_orang_tua' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam pengisian form.');
        }

        try {
            $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())->firstOrFail();

            // Handle upload foto
            $fotoPath = $calonMahasiswa->foto;
            if ($request->hasFile('foto')) {
                if ($calonMahasiswa->foto) {
                    $oldFilePath = str_replace('/storage/', '', $calonMahasiswa->foto);
                    if (Storage::disk('public')->exists($oldFilePath)) {
                        Storage::disk('public')->delete($oldFilePath);
                    }
                }

                $fotoFile = $request->file('foto');
                $fotoPath = 'foto-calon-mahasiswa/' . time() . '_' . $fotoFile->getClientOriginalName();
                Storage::disk('public')->put($fotoPath, file_get_contents($fotoFile));
                $fotoPath = '/storage/' . $fotoPath;
            }

            // Update data - HANYA field yang ada di database
            $calonMahasiswa->update([
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'asal_sekolah' => $request->asal_sekolah,
                'nama_orang_tua' => $request->nama_orang_tua,
                'pekerjaan_orang_tua' => $request->pekerjaan_orang_tua,
                'penghasilan_orang_tua' => $request->penghasilan_orang_tua,
                'no_telepon_orang_tua' => $request->no_telepon_orang_tua,
                'foto' => $fotoPath,
            ]);

            return redirect()->route('pendaftaran.pilih-prodi')
                ->with('success', 'Data diri berhasil disimpan! Silakan pilih program studi.');
        } catch (\Exception $e) {
            Log::error('Data diri save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form pilih program studi
     */
    public function showPilihProdiForm()
    {
        if (!Auth::check()) {
            return redirect()->route('pendaftaran.register');
        }

        $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())->first();
        $programStudi = ProgramStudi::with('fakultas')->get();

        if (!$calonMahasiswa) {
            return redirect()->route('pendaftaran.register');
        }

        return view('pendaftaran.pilih-prodi', compact('calonMahasiswa', 'programStudi'));
    }

    /**
     * Proses simpan pilihan program studi
     */
    public function simpanProdi(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('pendaftaran.register');
        }

        $validator = Validator::make($request->all(), [
            'kode_program_studi' => 'required|exists:program_studi,kode_program_studi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Silakan pilih program studi yang valid.');
        }

        try {
            $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())->firstOrFail();

            // Pastikan kode_program_studi ada di database
            $programStudi = ProgramStudi::where('kode_program_studi', $request->kode_program_studi)->first();

            if (!$programStudi) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Program studi tidak ditemukan.');
            }

            $calonMahasiswa->update([
                'kode_program_studi' => $request->kode_program_studi,
            ]);

            return redirect()->route('pendaftaran.pembayaran')
                ->with('success', 'Program studi berhasil dipilih! Silakan lanjutkan pembayaran.');
        } catch (\Exception $e) {
            Log::error('Program studi save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman pembayaran
     */
    public function showPembayaranForm()
    {
        if (!Auth::check()) {
            return redirect()->route('pendaftaran.register');
        }

        try {
            $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())
                ->with(['programStudi.fakultas', 'pembayaran'])
                ->firstOrFail();

            // Cek apakah data sudah lengkap
            if (!$calonMahasiswa->is_data_lengkap) {
                return redirect()->route('pendaftaran.data-diri')
                    ->with('error', 'Silakan lengkapi data diri terlebih dahulu.');
            }

            // Cek apakah sudah ada pembayaran yang berhasil
            if ($calonMahasiswa->sudah_bayar) {
                return redirect()->route('pendaftaran.status')
                    ->with('info', 'Anda sudah melakukan pembayaran.');
            }

            // Cek apakah ada pembayaran pending
            $pembayaranPending = $calonMahasiswa->pembayaran;
            $snapToken = null;

            if ($pembayaranPending && $pembayaranPending->is_pending && !$pembayaranPending->is_expired) {
                $snapToken = $pembayaranPending->snap_token;
            } else {
                // Buat pembayaran baru
                $snapToken = $this->generatePembayaran($calonMahasiswa);
            }

            return view('pendaftaran.pembayaran', compact('calonMahasiswa', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Pembayaran page error: ' . $e->getMessage());
            return redirect()->route('pendaftaran.status')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate pembayaran baru
     */
    private function generatePembayaran($calonMahasiswa)
    {
        try {
            DB::beginTransaction();

            // Hapus pembayaran lama yang expired
            Pembayaran::where('calon_mahasiswa_id', $calonMahasiswa->id)
                ->where('status', 'pending')
                ->delete();

            // Generate order ID
            $orderId = 'REG-' . $calonMahasiswa->id . '-' . time();
            $jumlah = 250000; // Rp 250.000
            $waktuKadaluarsa = now()->addHours(24); // Kadaluarsa 24 jam

            // Generate snap token
            $snapToken = $this->generateSnapToken($calonMahasiswa, $orderId, $jumlah);

            // Simpan data pembayaran
            $pembayaran = Pembayaran::create([
                'calon_mahasiswa_id' => $calonMahasiswa->id,
                'order_id' => $orderId,
                'jumlah' => $jumlah,
                'status' => 'pending',
                'snap_token' => $snapToken,
                'waktu_kadaluarsa' => $waktuKadaluarsa,
                'metadata' => ['created_at' => now()->toDateTimeString()]
            ]);

            DB::commit();

            Log::info('Payment created successfully', [
                'pembayaran_id' => $pembayaran->id,
                'order_id' => $orderId
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create payment: ' . $e->getMessage());
            throw new \Exception('Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Generate Snap Token untuk Midtrans
     */
    private function generateSnapToken($calonMahasiswa, $orderId, $jumlah)
    {
        // Validasi konfigurasi Midtrans
        if (empty(Config::$serverKey)) {
            throw new \Exception('Midtrans serverKey is not configured. Please set MIDTRANS_SERVER_KEY in your .env file.');
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $jumlah,
            ],
            'customer_details' => [
                'first_name' => $calonMahasiswa->nama_lengkap,
                'email' => Auth::user()->email,
                'phone' => $calonMahasiswa->no_telepon,
                'billing_address' => [
                    'first_name' => $calonMahasiswa->nama_lengkap,
                    'phone' => $calonMahasiswa->no_telepon,
                    'address' => $calonMahasiswa->alamat,
                ]
            ],
            'item_details' => [
                [
                    'id' => 'biaya-pendaftaran',
                    'price' => $jumlah,
                    'quantity' => 1,
                    'name' => 'Biaya Pendaftaran - ' . ($calonMahasiswa->programStudi->nama_program_studi ?? ''),
                    'brand' => 'Universitas Kita',
                    'category' => 'Pendaftaran Mahasiswa',
                    'merchant_name' => 'Universitas Kita'
                ]
            ],
            'callbacks' => [
                'finish' => route('pendaftaran.status')
            ]
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Snap token error: ' . $e->getMessage());
            throw new \Exception('Gagal generate token pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Handle callback dari Midtrans
     */
    public function handlePaymentCallback(Request $request)
    {
        try {
            $serverKey = config('services.midtrans.serverKey');

            if (empty($serverKey)) {
                Log::error('Midtrans serverKey not configured in callback');
                return response()->json(['status' => 'error', 'message' => 'Server configuration error'], 500);
            }

            // Verifikasi signature
            $hashed = hash(
                "sha512",
                $request->order_id .
                    $request->status_code .
                    $request->gross_amount .
                    $serverKey
            );

            if ($hashed !== $request->signature_key) {
                Log::error('Invalid signature key', [
                    'received' => $request->signature_key,
                    'calculated' => $hashed,
                    'order_id' => $request->order_id
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }

            $pembayaran = Pembayaran::where('order_id', $request->order_id)->first();

            if (!$pembayaran) {
                Log::error('Pembayaran not found', ['order_id' => $request->order_id]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            $status = strtolower($request->transaction_status);
            $fraudStatus = $request->fraud_status ?? '';

            Log::info('Payment callback received', [
                'order_id' => $request->order_id,
                'status' => $status,
                'fraud_status' => $fraudStatus
            ]);

            // Update status pembayaran
            $updateData = [
                'payment_type' => $request->payment_type,
                'metadata' => $request->all()
            ];

            if ($status === 'capture') {
                if ($fraudStatus === 'accept') {
                    $updateData['status'] = 'capture';
                } else {
                    $updateData['status'] = 'deny';
                }
            } elseif ($status === 'settlement') {
                $updateData['status'] = 'settlement';
            } elseif (in_array($status, ['pending', 'deny', 'expire', 'cancel'])) {
                $updateData['status'] = $status;
            }

            $pembayaran->update($updateData);

            // Jika pembayaran sukses, update status calon mahasiswa
            if (in_array($pembayaran->status, ['settlement', 'capture'])) {
                $pembayaran->calonMahasiswa->update([
                    'status_verifikasi' => 'menunggu_verifikasi',
                ]);

                Log::info('Payment successful, calon mahasiswa updated', [
                    'calon_mahasiswa_id' => $pembayaran->calon_mahasiswa_id,
                    'status' => $pembayaran->status
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error in payment callback: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook untuk handle notifikasi dari Midtrans
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Midtrans webhook received', $request->all());

        try {
            $notification = $request->all();
            $orderId = $notification['order_id'];
            $status = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? '';

            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                Log::error('Webhook: Order not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Handle status pembayaran
            $updateData = [
                'payment_type' => $notification['payment_type'] ?? null,
                'metadata' => $notification
            ];

            if ($status == 'capture') {
                if ($fraudStatus == 'accept') {
                    $updateData['status'] = 'capture';
                } else {
                    $updateData['status'] = 'deny';
                }
            } else if ($status == 'settlement') {
                $updateData['status'] = 'settlement';
            } else if (in_array($status, ['pending', 'deny', 'expire', 'cancel', 'refund'])) {
                $updateData['status'] = $status;
            }

            $pembayaran->update($updateData);

            // Jika pembayaran sukses, update status calon mahasiswa
            if (in_array($pembayaran->status, ['settlement', 'capture'])) {
                $pembayaran->calonMahasiswa->update([
                    'status_verifikasi' => 'menunggu_verifikasi',
                ]);

                Log::info('Webhook: Payment successful', [
                    'order_id' => $orderId,
                    'calon_mahasiswa_id' => $pembayaran->calon_mahasiswa_id
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage(), [
                'notification' => $request->all()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan status pendaftaran
     */
    public function status()
    {
        if (!Auth::check()) {
            return redirect()->route('pendaftaran.register');
        }

        $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())
            ->with(['programStudi.fakultas', 'pembayaran'])
            ->first();

        if (!$calonMahasiswa) {
            return redirect()->route('pendaftaran.register');
        }

        return view('pendaftaran.status', compact('calonMahasiswa'));
    }

    /**
     * Refresh snap token (jika expired)
     */
    public function refreshSnapToken()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())
                ->with('pembayaran')
                ->firstOrFail();

            // Hapus pembayaran lama yang expired
            if ($calonMahasiswa->pembayaran) {
                $calonMahasiswa->pembayaran->delete();
            }

            // Generate pembayaran baru
            $snapToken = $this->generatePembayaran($calonMahasiswa);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Refresh token error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle pembayaran manual
     */
    public function manualPayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $calonMahasiswa = CalonMahasiswa::where('user_id', Auth::id())->firstOrFail();

            // Buat record pembayaran manual
            $orderId = 'MANUAL-' . $calonMahasiswa->id . '-' . time();

            Pembayaran::create([
                'calon_mahasiswa_id' => $calonMahasiswa->id,
                'order_id' => $orderId,
                'jumlah' => 250000,
                'status' => 'pending_verification',
                'payment_type' => 'manual_transfer',
                'metadata' => [
                    'method' => 'manual_bank_transfer',
                    'confirmed_at' => now()->toDateTimeString(),
                    'note' => 'Menunggu verifikasi admin'
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Konfirmasi pembayaran manual berhasil. Admin akan memverifikasi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Manual payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout calon mahasiswa
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pendaftaran.register')
            ->with('success', 'Anda telah logout.');
    }

    /**
     * API untuk mengecek ketersediaan email
     */
    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email sudah terdaftar' : 'Email tersedia'
        ]);
    }
}
