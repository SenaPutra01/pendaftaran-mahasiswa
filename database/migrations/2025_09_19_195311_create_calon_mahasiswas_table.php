<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calon_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_lengkap', 100);
            $table->string('jenis_kelamin', 10)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->string('asal_sekolah', 100)->nullable();
            $table->string('foto')->nullable();
            $table->string('kode_program_studi', 10)->nullable();

            $table->string('nik', 16)->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('nama_orang_tua', 100)->nullable();
            $table->string('pekerjaan_orang_tua', 50)->nullable();
            $table->string('penghasilan_orang_tua', 50)->nullable();
            $table->string('no_telepon_orang_tua', 15)->nullable();

            $table->string('status_verifikasi', 20)->default('belum_verifikasi');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->text('catatan_verifikasi')->nullable();

            $table->timestamps();

            $table->foreign('kode_program_studi')
                ->references('kode_program_studi')
                ->on('program_studi')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['kode_program_studi']);
        });
        Schema::dropIfExists('calon_mahasiswa');
    }
};
