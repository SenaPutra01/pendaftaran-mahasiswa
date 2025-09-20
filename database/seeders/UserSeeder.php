<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CalonMahasiswa;
use App\Models\ProgramStudi;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Hapus data existing dengan cara yang aman untuk PostgreSQL
        DB::table('calon_mahasiswa')->delete();
        DB::table('users')->delete();

        // 1. Buat user Administrator
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@universitas.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
            'email_verified_at' => now(),
        ]);

        $this->command->info('User Administrator created: admin@universitas.ac.id / password123');

        // 2. Buat user Staff Administrasi
        $staff = User::create([
            'name' => 'Staff Administrasi',
            'email' => 'staff@universitas.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
            'email_verified_at' => now(),
        ]);

        $this->command->info('User Staff created: staff@universitas.ac.id / password123');

        // 3. Buat beberapa calon mahasiswa contoh
        $programStudi = ProgramStudi::all();

        if ($programStudi->count() > 0) {
            $calonMahasiswaData = [
                [
                    'name' => 'Ahmad Rizki',
                    'email' => 'ahmad.rizki@example.com',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '2000-05-15',
                    'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                    'no_telepon' => '081234567891',
                    'asal_sekolah' => 'SMA Negeri 1 Jakarta',
                    'program_studi' => 'TI' // Teknik Informatika
                ],
                [
                    'name' => 'Siti Rahayu',
                    'email' => 'siti.rahayu@example.com',
                    'jenis_kelamin' => 'Perempuan',
                    'tanggal_lahir' => '2001-08-22',
                    'alamat' => 'Jl. Sudirman No. 45, Bandung',
                    'no_telepon' => '081234567892',
                    'asal_sekolah' => 'SMA Negeri 2 Bandung',
                    'program_studi' => 'SI' // Sistem Informasi
                ],
                [
                    'name' => 'Budi Santoso',
                    'email' => 'budi.santoso@example.com',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '1999-12-10',
                    'alamat' => 'Jl. Gajah Mada No. 67, Surabaya',
                    'no_telepon' => '081234567893',
                    'asal_sekolah' => 'SMA Negeri 3 Surabaya',
                    'program_studi' => 'MNJ' // Manajemen
                ],
                [
                    'name' => 'Dewi Lestari',
                    'email' => 'dewi.lestari@example.com',
                    'jenis_kelamin' => 'Perempuan',
                    'tanggal_lahir' => '2000-03-25',
                    'alamat' => 'Jl. Diponegoro No. 89, Yogyakarta',
                    'no_telepon' => '081234567894',
                    'asal_sekolah' => 'SMA Negeri 4 Yogyakarta',
                    'program_studi' => 'AKT' // Akuntansi
                ],
                [
                    'name' => 'Rudi Hermawan',
                    'email' => 'rudi.hermawan@example.com',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '2001-07-18',
                    'alamat' => 'Jl. Thamrin No. 34, Medan',
                    'no_telepon' => '081234567895',
                    'asal_sekolah' => 'SMA Negeri 5 Medan',
                    'program_studi' => 'HKM' // Ilmu Hukum
                ]
            ];

            foreach ($calonMahasiswaData as $data) {
                // Cari program studi berdasarkan kode
                $prodi = $programStudi->firstWhere('kode_program_studi', $data['program_studi']);

                if ($prodi) {
                    // Buat user calon mahasiswa
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make('password123'),
                        'role' => 'mahasiswa',
                        'email_verified_at' => now(),
                    ]);

                    // Buat data calon mahasiswa
                    CalonMahasiswa::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $data['name'],
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'tanggal_lahir' => $data['tanggal_lahir'],
                        'alamat' => $data['alamat'],
                        'no_telepon' => $data['no_telepon'],
                        'asal_sekolah' => $data['asal_sekolah'],
                        'kode_program_studi' => $prodi->kode_program_studi,
                    ]);

                    $this->command->info("Calon mahasiswa created: {$data['email']} / password123");
                }
            }
        }

        // 4. Buat user calon mahasiswa tambahan (3 user saja untuk testing)
        for ($i = 1; $i <= 3; $i++) {
            $prodi = $programStudi->random();

            $user = User::create([
                'name' => 'Calon Mahasiswa ' . $i,
                'email' => 'calon' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]);

            CalonMahasiswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => 'Calon Mahasiswa ' . $i,
                'jenis_kelamin' => $i % 2 == 0 ? 'Laki-laki' : 'Perempuan',
                'tanggal_lahir' => now()->subYears(rand(17, 22))->subMonths(rand(1, 12))->subDays(rand(1, 30)),
                'alamat' => 'Jl. Contoh No. ' . $i . ', Kota Contoh',
                'no_telepon' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'asal_sekolah' => 'SMA Negeri ' . $i . ' Contoh',
                'kode_program_studi' => $prodi->kode_program_studi,
            ]);

            $this->command->info("Calon mahasiswa created: calon{$i}@example.com / password123");
        }

        $this->command->info('Seeder berhasil dijalankan!');
        $this->command->info('Total user: ' . User::count());
        $this->command->info('Total calon mahasiswa: ' . CalonMahasiswa::count());
    }
}
