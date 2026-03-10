<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_images', function (Blueprint $blueprint) {
            $blueprint->id();
            // Foreign key ke tabel reports
            // constrained() berasumsi nama tabelnya adalah 'reports'
            $blueprint->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $blueprint->string('path'); // Menyimpan lokasi file gambar
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_images');
    }
};