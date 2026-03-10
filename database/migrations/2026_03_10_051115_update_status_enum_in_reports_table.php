<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom status menjadi list ENUM yang baru
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM(
            'Proposal', 
            'Verifikasi', 
            'Penetapan', 
            'Pelaksanaan', 
            'Pemeriksaan', 
            'Selesai'
        ) NOT NULL DEFAULT 'Proposal'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke ENUM yang lama jika di-rollback
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM(
            'Menunggu', 
            'Proses', 
            'Selesai', 
            'Ditolak'
        ) NOT NULL DEFAULT 'Menunggu'");
    }
};