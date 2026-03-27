<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggunaan_rab_logs', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel jenis_rabs
            $table->foreignId('jenis_rab_id')
                  ->constrained('jenis_rabs')
                  ->onDelete('cascade');
            
            $table->bigInteger('nominal_penggunaan'); // Jumlah yang dipakai
            $table->bigInteger('saldo_awal');         // Saldo sebelum dipotong
            $table->bigInteger('saldo_akhir');        // Saldo setelah dipotong
            $table->string('keterangan')->nullable(); // Alasan/keperluan
            $table->timestamps(); // Mencatat created_at (waktu transaksi)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggunaan_rab_logs');
    }
};