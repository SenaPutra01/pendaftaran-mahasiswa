<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas'; // Nama tabel eksplisit
    protected $primaryKey = 'kode_fakultas';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_fakultas',
        'nama_fakultas',
        'deskripsi'
    ];

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class, 'kode_fakultas', 'kode_fakultas');
    }

    public function calonMahasiswa()
    {
        return $this->hasManyThrough(
            CalonMahasiswa::class,
            ProgramStudi::class,
            'kode_fakultas',
            'kode_program_studi',
            'kode_fakultas',
            'kode_program_studi'
        );
    }
}
