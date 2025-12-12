<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CalonMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'calon_mahasiswa';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'kode_program_studi',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'no_telepon',
        'asal_sekolah',
        'nik',
        'nama_orang_tua',
        'pekerjaan_orang_tua',
        'penghasilan_orang_tua',
        'no_telepon_orang_tua',
        'foto',
        'status_verifikasi',
        'tanggal_verifikasi',
        'catatan_verifikasi'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_verifikasi' => 'datetime',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke program studi
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'kode_program_studi', 'kode_program_studi');
    }

    /**
     * Relasi ke pembayaran (hasMany untuk riwayat pembayaran)
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'calon_mahasiswa_id');
    }

    /**
     * Relasi ke semua riwayat pembayaran (jika perlu riwayat)
     */
    public function semuaPembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'calon_mahasiswa_id')->latest();
    }

    /**
     * Accessor untuk URL foto
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {

            if (strpos($this->foto, 'http') === 0) {
                return $this->foto;
            }
            if (strpos($this->foto, '/storage/') === 0) {
                return url($this->foto);
            }
            return Storage::url($this->foto);
        }

        return asset('images/default-profile.png');
    }

    /**
     * Accessor untuk usia
     */
    public function getUsiaAttribute()
    {
        if ($this->tanggal_lahir) {
            return Carbon::parse($this->tanggal_lahir)->age;
        }
        return null;
    }

    /**
     * Cek apakah data lengkap untuk pembayaran
     * (Lebih ketat sesuai kebutuhan pendaftaran)
     */
    public function getIsDataLengkapAttribute()
    {
        $requiredFields = [
            'nik' => $this->nik,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'agama' => $this->agama,
            'alamat' => $this->alamat,
            'no_telepon' => $this->no_telepon,
            'jenis_kelamin' => $this->jenis_kelamin,
            'asal_sekolah' => $this->asal_sekolah,
            'nama_orang_tua' => $this->nama_orang_tua,
            'pekerjaan_orang_tua' => $this->pekerjaan_orang_tua,
            'kode_program_studi' => $this->kode_program_studi,
        ];

        foreach ($requiredFields as $field => $value) {
            if (empty($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Cek apakah sudah melakukan pembayaran yang berhasil
     * (Menggunakan constant dari model Pembayaran)
     */
    public function getSudahBayarAttribute()
    {
        if (!$this->pembayaran) {
            return false;
        }

        return $this->pembayaran->is_paid;
    }

    /**
     * Cek apakah memiliki pembayaran aktif (pending dan belum expired)
     */
    public function getMemilikiPembayaranAktifAttribute()
    {
        if (!$this->pembayaran) {
            return false;
        }

        return $this->pembayaran->is_active;
    }

    /**
     * Cek apakah pembayaran expired
     */
    public function getPembayaranExpiredAttribute()
    {
        if (!$this->pembayaran) {
            return false;
        }

        return $this->pembayaran->is_expired;
    }

    /**
     * Cek status pembayaran dengan label yang lebih informatif
     */
    public function getStatusPembayaranAttribute()
    {
        if (!$this->pembayaran) {
            return [
                'status' => 'unpaid',
                'label' => 'Belum Bayar',
                'color' => 'secondary'
            ];
        }

        return [
            'status' => $this->pembayaran->status,
            'label' => $this->pembayaran->status_label,
            'color' => $this->pembayaran->status_color
        ];
    }

    /**
     * Get pembayaran terbaru
     */
    public function getPembayaranTerbaruAttribute()
    {
        return $this->pembayaran;
    }

    /**
     * Cek apakah bisa membuat pembayaran baru
     */
    public function bisaBuatPembayaranBaru()
    {

        if ($this->sudah_bayar) {
            return false;
        }


        if ($this->memiliki_pembayaran_aktif) {
            return false;
        }


        if (!$this->is_data_lengkap) {
            return false;
        }

        return true;
    }

    /**
     * Get status verifikasi label
     */
    public function getStatusVerifikasiLabelAttribute()
    {
        $labels = [
            'belum_verifikasi' => 'Belum Diverifikasi',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
        ];

        return $labels[$this->status_verifikasi] ?? $this->status_verifikasi;
    }

    /**
     * Get status verifikasi color
     */
    public function getStatusVerifikasiColorAttribute()
    {
        $colors = [
            'belum_verifikasi' => 'secondary',
            'menunggu_verifikasi' => 'warning',
            'diverifikasi' => 'success',
            'ditolak' => 'danger',
        ];

        return $colors[$this->status_verifikasi] ?? 'secondary';
    }

    /**
     * Format tanggal lahir untuk form input
     */
    public function getTanggalLahirFormattedAttribute()
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->format('Y-m-d');
        }
        return null;
    }

    /**
     * Format tanggal lahir untuk display
     */
    public function getTanggalLahirDisplayAttribute()
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->format('d F Y');
        }
        return '-';
    }

    /**
     * Get alamat singkat (max 50 karakter)
     */
    public function getAlamatSingkatAttribute()
    {
        if (strlen($this->alamat) > 50) {
            return substr($this->alamat, 0, 50) . '...';
        }
        return $this->alamat;
    }

    /**
     * Cek apakah masih dalam proses pendaftaran
     */
    public function getDalamProsesPendaftaranAttribute()
    {
        return !$this->sudah_bayar && $this->is_data_lengkap;
    }

    /**
     * Cek apakah sudah menyelesaikan semua tahap
     */
    public function getSelesaiPendaftaranAttribute()
    {
        return $this->sudah_bayar &&
            $this->status_verifikasi === 'menunggu_verifikasi';
    }

    /**
     * Scope untuk calon mahasiswa yang sudah bayar
     */
    public function scopeSudahBayar($query)
    {
        return $query->whereHas('pembayaran', function ($q) {
            $q->whereIn('status', Pembayaran::getSuccessfulStatuses());
        });
    }

    /**
     * Scope untuk calon mahasiswa yang belum bayar
     */
    public function scopeBelumBayar($query)
    {
        return $query->whereDoesntHave('pembayaran')
            ->orWhereHas('pembayaran', function ($q) {
                $q->whereNotIn('status', Pembayaran::getSuccessfulStatuses());
            });
    }

    /**
     * Scope untuk calon mahasiswa yang menunggu verifikasi
     */
    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status_verifikasi', 'menunggu_verifikasi');
    }

    /**
     * Scope untuk calon mahasiswa yang sudah diverifikasi
     */
    public function scopeSudahDiverifikasi($query)
    {
        return $query->where('status_verifikasi', 'diverifikasi');
    }

    /**
     * Scope untuk calon mahasiswa dengan data lengkap
     */
    public function scopeDataLengkap($query)
    {
        return $query->whereNotNull('nik')
            ->whereNotNull('tempat_lahir')
            ->whereNotNull('tanggal_lahir')
            ->whereNotNull('alamat')
            ->whereNotNull('no_telepon')
            ->whereNotNull('kode_program_studi')
            ->whereNotNull('nama_orang_tua');
    }

    /**
     * Update status verifikasi
     */
    public function updateStatusVerifikasi($status, $catatan = null)
    {
        $updateData = [
            'status_verifikasi' => $status,
            'tanggal_verifikasi' => now(),
        ];

        if ($catatan) {
            $updateData['catatan_verifikasi'] = $catatan;
        }

        return $this->update($updateData);
    }

    /**
     * Boot method untuk event listeners
     */
    protected static function boot()
    {
        parent::boot();


        static::updated(function ($model) {

            if ($model->pembayaran && $model->pembayaran->is_paid && empty($model->tanggal_pembayaran)) {
                $model->update(['tanggal_pembayaran' => now()]);
            }
        });
    }
}
