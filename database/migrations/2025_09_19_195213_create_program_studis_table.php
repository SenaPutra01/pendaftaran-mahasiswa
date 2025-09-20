<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('program_studi', function (Blueprint $table) {
            // $table->id();
            $table->string('kode_program_studi', 10)->primary();
            $table->string('nama_program_studi', 100);
            $table->string('kode_fakultas', 10);
            $table->enum('jenjang', ['D3', 'S1', 'S2', 'S3'])->default('S1');
            $table->decimal('biaya_pendaftaran', 10, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('kode_fakultas')
                ->references('kode_fakultas')
                ->on('fakultas')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('program_studi', function (Blueprint $table) {
            $table->dropForeign(['kode_fakultas']);
        });
        Schema::dropIfExists('program_studi');
    }
};
