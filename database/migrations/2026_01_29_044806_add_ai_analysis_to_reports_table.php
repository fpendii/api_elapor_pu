<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Kolom untuk menyimpan deskripsi analisis dari AI
            $table->text('ai_analysis')->nullable()->after('foto_kerusakan');

            // Kolom untuk label kategori kerusakan otomatis (misal: Lubang, Retak, Pipa Pecah)
            $table->string('ai_damage_type')->nullable()->after('ai_analysis');

            // Kolom untuk tingkat keparahan berdasarkan AI (misal: Rendah, Sedang, Tinggi)
            $table->string('ai_severity')->nullable()->after('ai_damage_type');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['ai_analysis', 'ai_damage_type', 'ai_severity']);
        });
    }
};
