<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
     * Relasi ke pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'calon_mahasiswa_id');
    }

    /**
     * Accessor untuk URL foto
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }

        return asset('images/default-profile.png');
    }

    /**
     * Accessor untuk usia
     */
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir->age;
    }

    /**
     * Cek apakah data lengkap untuk pembayaran
     */
    public function getIsDataLengkapAttribute()
    {
        return !empty($this->nik) &&
            !empty($this->tempat_lahir) &&
            !empty($this->agama) &&
            !empty($this->nama_orang_tua) &&
            !empty($this->kode_program_studi);
    }

    /**
     * Cek apakah sudah bayar
     */
    public function getSudahBayarAttribute()
    {
        return $this->pembayaran && $this->pembayaran->is_paid;
    }

    /**
     * Cek status pembayaran
     */
    public function getStatusPembayaranAttribute()
    {
        if ($this->pembayaran) {
            return $this->pembayaran->status;
        }
        return 'unpaid';
    }
}
