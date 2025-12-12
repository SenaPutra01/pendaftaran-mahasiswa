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
use Midtrans\Notification;
use Midtrans\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    public function __construct()
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        if (empty($serverKey) || empty($clientKey)) {
            Log::error('Midtrans configuration missing: serverKey or clientKey is empty');
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

            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]);

            CalonMahasiswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'status_verifikasi' => 'belum_verifikasi',
            ]);

            DB::commit();

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

            if (!$calonMahasiswa->is_data_lengkap) {
                return redirect()->route('pendaftaran.data-diri')
                    ->with('error', 'Silakan lengkapi data diri terlebih dahulu.');
            }

            if ($calonMahasiswa->sudah_bayar) {
                return redirect()->route('pendaftaran.status')
                    ->with('info', 'Anda sudah melakukan pembayaran.');
            }

            $pembayaranPending = $calonMahasiswa->pembayaran;
            $snapToken = null;

            if ($pembayaranPending && $pembayaranPending->is_pending && !$pembayaranPending->is_expired) {
                $snapToken = $pembayaranPending->snap_token;
            } else {
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

            if (!$calonMahasiswa->bisaBuatPembayaranBaru()) {
                throw new \Exception('Tidak dapat membuat pembayaran baru. ' .
                    'Pastikan data sudah lengkap dan tidak ada pembayaran aktif.');
            }

            Pembayaran::where('calon_mahasiswa_id', $calonMahasiswa->id)
                ->where('status', Pembayaran::STATUS_PENDING)
                ->where('waktu_kadaluarsa', '<', now())
                ->delete();

            $orderId = 'REG-' . $calonMahasiswa->id . '-' . time();
            $jumlah = 250000;
            $waktuKadaluarsa = now()->addHours(24);

            $snapToken = $this->generateSnapToken($calonMahasiswa, $orderId, $jumlah);

            $pembayaran = Pembayaran::create([
                'calon_mahasiswa_id' => $calonMahasiswa->id,
                'order_id' => $orderId,
                'jumlah' => $jumlah,
                'status' => Pembayaran::STATUS_PENDING,
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
     * Handle callback dari Midtrans (frontend redirect)
     */
    public function handlePaymentCallback(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK START ===');
        Log::info('Callback Data:', $request->all());

        try {
            $orderId = $request->order_id;

            if (!$orderId) {
                Log::error('No order_id in callback request');
                return redirect()->route('pendaftaran.status')
                    ->with('error', 'Data callback tidak valid.');
            }

            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                Log::error('Pembayaran not found', ['order_id' => $orderId]);
                return redirect()->route('pendaftaran.status')
                    ->with('error', 'Transaksi tidak ditemukan.');
            }

            if ($request->has('transaction_status')) {
                $status = strtolower($request->transaction_status);

                $statusMapping = [
                    'capture' => Pembayaran::STATUS_CAPTURE,
                    'settlement' => Pembayaran::STATUS_SETTLEMENT,
                    'pending' => Pembayaran::STATUS_PENDING,
                    'deny' => Pembayaran::STATUS_DENY,
                    'expire' => Pembayaran::STATUS_EXPIRE,
                    'cancel' => Pembayaran::STATUS_CANCEL,
                    'refund' => Pembayaran::STATUS_REFUND
                ];

                $newStatus = $statusMapping[$status] ?? Pembayaran::STATUS_PENDING;

                $updateData = [
                    'status' => $newStatus,
                    'payment_type' => $request->payment_type ?? null,
                    'metadata' => array_merge(
                        $pembayaran->metadata ?? [],
                        ['callback_data' => $request->all()]
                    )
                ];

                $pembayaran->update($updateData);

                if (in_array($newStatus, Pembayaran::getSuccessfulStatuses())) {
                    $pembayaran->calonMahasiswa()->update([
                        'status_verifikasi' => 'menunggu_verifikasi',
                    ]);

                    Log::info('Calon mahasiswa updated from callback', [
                        'order_id' => $orderId,
                        'status' => $newStatus
                    ]);
                }

                Log::info('Payment updated from callback', [
                    'order_id' => $orderId,
                    'status' => $newStatus
                ]);
            }

            return redirect()->route('pendaftaran.status')
                ->with('info', 'Status pembayaran akan diperbarui otomatis.');
        } catch (\Exception $e) {
            Log::error('Error in payment callback: ' . $e->getMessage());
            return redirect()->route('pendaftaran.status')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Webhook untuk handle notifikasi dari Midtrans
     */
    public function handleWebhook(Request $request)
    {
        try {
            $notification = $request->all();

            if (empty($notification['order_id']) || empty($notification['transaction_status'])) {
                Log::error('Invalid webhook data', $notification);
                return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
            }

            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? null;

            Log::info('Processing webhook', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                Log::error('Payment not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            Log::info('Found payment', [
                'current_status' => $pembayaran->status,
                'calon_mahasiswa_id' => $pembayaran->calon_mahasiswa_id
            ]);

            $statusMapping = [
                'capture' => Pembayaran::STATUS_CAPTURE,
                'settlement' => Pembayaran::STATUS_SETTLEMENT,
                'pending' => Pembayaran::STATUS_PENDING,
                'deny' => Pembayaran::STATUS_DENY,
                'expire' => Pembayaran::STATUS_EXPIRE,
                'cancel' => Pembayaran::STATUS_CANCEL,
                'refund' => Pembayaran::STATUS_REFUND
            ];

            $newStatus = $statusMapping[$transactionStatus] ?? Pembayaran::STATUS_PENDING;

            if ($transactionStatus === 'capture' && $fraudStatus === 'challenge') {
                $newStatus = Pembayaran::STATUS_PENDING;
                Log::info('Payment challenged by fraud system');
            }

            $pembayaran->update([
                'status' => $newStatus,
                'payment_type' => $notification['payment_type'] ?? null,
                'metadata' => array_merge(
                    $pembayaran->metadata ?? [],
                    [
                        'webhook_received_at' => now()->toISOString(),
                        'webhook_data' => $notification
                    ]
                )
            ]);

            Log::info('Payment updated', [
                'order_id' => $orderId,
                'old_status' => $pembayaran->getOriginal('status'),
                'new_status' => $newStatus
            ]);

            if (in_array($newStatus, Pembayaran::getSuccessfulStatuses())) {
                $calonMahasiswa = $pembayaran->calonMahasiswa;
                if ($calonMahasiswa) {
                    $calonMahasiswa->update([
                        'status_verifikasi' => 'menunggu_verifikasi',
                    ]);

                    Log::info('Calon mahasiswa updated', [
                        'calon_mahasiswa_id' => $calonMahasiswa->id,
                        'status_verifikasi' => 'menunggu_verifikasi'
                    ]);
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function syncPaymentStatus($orderId)
    {
        try {
            $pembayaran = Pembayaran::where('order_id', $orderId)->firstOrFail();

            Log::info('Manual sync started', [
                'order_id' => $orderId,
                'current_status' => $pembayaran->status
            ]);

            /** @var \stdClass $status */
            $status = Transaction::status($orderId);

            if (is_array($status)) {
                $status = (object) $status;
            }

            $getStatusProperty = function ($key) use ($status) {
                if (is_object($status)) {
                    return $status->$key ?? null;
                }
                if (is_array($status)) {
                    return $status[$key] ?? null;
                }
                return null;
            };

            Log::info('Midtrans API response:', [
                'order_id' => $orderId,
                'transaction_status' => $getStatusProperty('transaction_status'),
                'fraud_status' => $getStatusProperty('fraud_status')
            ]);

            $statusMapping = [
                'capture' => Pembayaran::STATUS_CAPTURE,
                'settlement' => Pembayaran::STATUS_SETTLEMENT,
                'pending' => Pembayaran::STATUS_PENDING,
                'deny' => Pembayaran::STATUS_DENY,
                'expire' => Pembayaran::STATUS_EXPIRE,
                'cancel' => Pembayaran::STATUS_CANCEL,
                'refund' => Pembayaran::STATUS_REFUND
            ];

            $midtransStatus = $getStatusProperty('transaction_status');
            $newStatus = $statusMapping[$midtransStatus] ?? Pembayaran::STATUS_PENDING;

            if ($midtransStatus === 'capture' && $getStatusProperty('fraud_status') === 'challenge') {
                $newStatus = Pembayaran::STATUS_PENDING;
            }

            if ($pembayaran->status !== $newStatus) {
                $updateData = [
                    'status' => $newStatus,
                    'payment_type' => $getStatusProperty('payment_type'),
                    'metadata' => array_merge(
                        $pembayaran->metadata ?? [],
                        [
                            'manual_sync_at' => now()->toDateTimeString(),
                            'midtrans_status_data' => is_object($status)
                                ? json_decode(json_encode($status), true)
                                : $status
                        ]
                    )
                ];

                $pembayaran->update($updateData);

                if (in_array($newStatus, Pembayaran::getSuccessfulStatuses())) {
                    $pembayaran->calonMahasiswa()->update([
                        'status_verifikasi' => 'menunggu_verifikasi',
                    ]);
                }

                Log::info('Manual sync successful', [
                    'order_id' => $orderId,
                    'old_status' => $pembayaran->getOriginal('status'),
                    'new_status' => $newStatus
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status updated',
                    'old_status' => $pembayaran->getOriginal('status'),
                    'new_status' => $newStatus,
                    'midtrans_status' => $midtransStatus
                ]);
            }

            Log::info('No update needed', [
                'order_id' => $orderId,
                'status' => $pembayaran->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'No update needed',
                'current_status' => $pembayaran->status,
                'midtrans_status' => $midtransStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Sync payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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

            if ($calonMahasiswa->pembayaran && $calonMahasiswa->pembayaran->is_expired) {
                $calonMahasiswa->pembayaran->delete();
            } else if ($calonMahasiswa->pembayaran && !$calonMahasiswa->pembayaran->is_expired) {
                return response()->json([
                    'error' => 'Masih ada pembayaran aktif yang belum expired'
                ], 400);
            }

            $snapToken = $this->generatePembayaran($calonMahasiswa);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            Log::error('Refresh token error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
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

            if ($calonMahasiswa->memiliki_pembayaran_aktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Masih ada pembayaran aktif. Silakan selesaikan atau tunggu hingga expired.'
                ], 400);
            }

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
