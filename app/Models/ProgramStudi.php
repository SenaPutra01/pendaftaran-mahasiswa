<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';
    protected $primaryKey = 'kode_program_studi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_program_studi',
        'nama_program_studi',
        'kode_fakultas',
        'jenjang',
        'biaya_pendaftaran',
        'deskripsi'
    ];

    protected $casts = [
        'biaya_pendaftaran' => 'decimal:2',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'kode_fakultas', 'kode_fakultas');
    }

    public function calonMahasiswa()
    {
        return $this->hasMany(CalonMahasiswa::class, 'kode_program_studi', 'kode_program_studi');
    }


    public function scopeByFakultas($query, $kodeFakultas)
    {
        return $query->where('kode_fakultas', $kodeFakultas);
    }


    public function scopeByJenjang($query, $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }


    public function getNamaLengkapAttribute()
    {
        return $this->jenjang . ' ' . $this->nama_program_studi;
    }
}
