<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'calon_mahasiswa_id',
        'order_id',
        'jumlah',
        'status',
        'payment_type',
        'snap_token',
        'waktu_kadaluarsa',
        'metadata'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'waktu_kadaluarsa' => 'datetime',
        'metadata' => 'array', // Pastikan ini ada
    ];

    /**
     * Relasi ke calon mahasiswa
     */
    public function calonMahasiswa()
    {
        return $this->belongsTo(CalonMahasiswa::class, 'calon_mahasiswa_id');
    }

    /**
     * Cek apakah pembayaran sudah kadaluarsa
     */
    public function getIsExpiredAttribute()
    {
        if ($this->waktu_kadaluarsa) {
            return now()->greaterThan($this->waktu_kadaluarsa);
        }
        return false;
    }

    /**
     * Cek apakah pembayaran berhasil
     */
    public function getIsPaidAttribute()
    {
        return in_array($this->status, ['settlement', 'capture']);
    }

    /**
     * Cek apakah pembayaran pending
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Cek apakah menunggu verifikasi manual
     */
    public function getIsPendingVerificationAttribute()
    {
        return $this->status === 'pending_verification';
    }
}
