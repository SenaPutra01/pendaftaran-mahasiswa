<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Fakultas;
use App\Models\ProgramStudi;

class FakultasProgramStudiSeeder extends Seeder
{
    public function run()
    {

        DB::table('program_studi')->delete();
        DB::table('fakultas')->delete();


        $fakultasData = [
            ['kode_fakultas' => 'FTI', 'nama_fakultas' => 'Fakultas Teknologi Informasi', 'deskripsi' => 'Fakultas yang fokus pada pengembangan teknologi informasi dan komputer'],
            ['kode_fakultas' => 'FE', 'nama_fakultas' => 'Fakultas Ekonomi', 'deskripsi' => 'Fakultas yang mempelajari ilmu ekonomi dan bisnis'],
            ['kode_fakultas' => 'FH', 'nama_fakultas' => 'Fakultas Hukum', 'deskripsi' => 'Fakultas yang mempelajari ilmu hukum dan perundang-undangan'],
            ['kode_fakultas' => 'FIP', 'nama_fakultas' => 'Fakultas Ilmu Pendidikan', 'deskripsi' => 'Fakultas yang mempelajari ilmu pendidikan dan keguruan'],
        ];

        foreach ($fakultasData as $data) {
            Fakultas::create($data);
        }


        $programStudiData = [

            ['kode_program_studi' => 'TI', 'nama_program_studi' => 'Teknik Informatika', 'kode_fakultas' => 'FTI', 'jenjang' => 'S1', 'biaya_pendaftaran' => 250000, 'deskripsi' => 'Program studi teknik informatika'],
            ['kode_program_studi' => 'SI', 'nama_program_studi' => 'Sistem Informasi', 'kode_fakultas' => 'FTI', 'jenjang' => 'S1', 'biaya_pendaftaran' => 250000, 'deskripsi' => 'Program studi sistem informasi'],
            ['kode_program_studi' => 'TK', 'nama_program_studi' => 'Teknik Komputer', 'kode_fakultas' => 'FTI', 'jenjang' => 'D3', 'biaya_pendaftaran' => 200000, 'deskripsi' => 'Program studi teknik komputer'],


            ['kode_program_studi' => 'MNJ', 'nama_program_studi' => 'Manajemen', 'kode_fakultas' => 'FE', 'jenjang' => 'S1', 'biaya_pendaftaran' => 225000, 'deskripsi' => 'Program studi manajemen'],
            ['kode_program_studi' => 'AKT', 'nama_program_studi' => 'Akuntansi', 'kode_fakultas' => 'FE', 'jenjang' => 'S1', 'biaya_pendaftaran' => 225000, 'deskripsi' => 'Program studi akuntansi'],


            ['kode_program_studi' => 'HKM', 'nama_program_studi' => 'Ilmu Hukum', 'kode_fakultas' => 'FH', 'jenjang' => 'S1', 'biaya_pendaftaran' => 200000, 'deskripsi' => 'Program studi ilmu hukum'],


            ['kode_program_studi' => 'PGSD', 'nama_program_studi' => 'Pendidikan Guru Sekolah Dasar', 'kode_fakultas' => 'FIP', 'jenjang' => 'S1', 'biaya_pendaftaran' => 175000, 'deskripsi' => 'Program studi pendidikan guru SD'],
            ['kode_program_studi' => 'PAUD', 'nama_program_studi' => 'Pendidikan Anak Usia Dini', 'kode_fakultas' => 'FIP', 'jenjang' => 'S1', 'biaya_pendaftaran' => 175000, 'deskripsi' => 'Program studi pendidikan anak usia dini'],
        ];

        foreach ($programStudiData as $data) {
            ProgramStudi::create($data);
        }

        $this->command->info('Seeder Fakultas dan Program Studi berhasil dijalankan!');
        $this->command->info('Total fakultas: ' . Fakultas::count());
        $this->command->info('Total program studi: ' . ProgramStudi::count());
    }
}
