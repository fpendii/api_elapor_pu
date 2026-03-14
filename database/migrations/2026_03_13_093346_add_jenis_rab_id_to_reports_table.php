<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $blueprint) {
            // 1. Menambahkan kolom jenis_rab_id
            // Kita gunakan unsignedBigInteger karena referensinya adalah ID (bigint unsigned)
            $blueprint->unsignedBigInteger('jenis_rab_id')->nullable()->after('user_id');

            // 2. Membuat Foreign Key agar relasi terkunci ke tabel jenis_rabs
            $blueprint->foreign('jenis_rab_id')
                      ->references('id')
                      ->on('jenis_rabs')
                      ->onDelete('set null'); // Jika jenis RAB dihapus, laporan tidak ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $blueprint) {
            // Menghapus foreign key terlebih dahulu sebelum menghapus kolom
            $blueprint->dropForeign(['jenis_rab_id']);
            $blueprint->dropColumn('jenis_rab_id');
        });
    }
};