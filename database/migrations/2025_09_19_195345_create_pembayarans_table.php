<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('calon_mahasiswa_id')->constrained()->onDelete('cascade');
            $table->foreignId('calon_mahasiswa_id')->constrained('calon_mahasiswa')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->decimal('jumlah', 10, 2);
            $table->enum('status', ['pending', 'settlement', 'capture', 'deny', 'cancel', 'expire', 'refund'])->default('pending');
            $table->string('payment_type')->nullable();
            $table->string('snap_token')->nullable();
            $table->datetime('waktu_kadaluarsa')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['calon_mahasiswa_id']);
        });
        Schema::dropIfExists('pembayaran');
    }
};
