<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\ReportImage;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID user yang rolenya 'user'
        $users = User::where('role', 'user')->pluck('id');

        // Jika user kosong, buat user dummy
        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create([
                'role' => 'user',
                'verifikasi' => 'acc'
            ])->pluck('id');
        }

        $kategori = ['Jalan Rusak', 'Lampu Jalan', 'Sampah', 'Banjir', 'Fasilitas Umum'];
        
        // Daftar Status Baru sesuai alur kerja PU
        $statuses = [
            'Proposal', 
            'Verifikasi', 
            'Penetapan', 
            'Pelaksanaan', 
            'Pemeriksaan', 
            'Selesai'
        ];

        for ($i = 1; $i <= 50; $i++) {
            // 1. Buat data Report
            $report = Report::create([
                'user_id' => $users->random(),
                'jenis_usulan' => collect(['Infrastruktur', 'Fasilitas Publik', 'Darurat'])->random(),
                'kategori' => $kategori[array_rand($kategori)],
                'judul' => 'Laporan Kerusakan #' . $i,
                'lokasi' => 'Kelurahan Contoh RT ' . rand(1, 10),
                'deskripsi' => 'Deskripsi detail untuk laporan kerusakan ke-' . $i,
                'foto_kerusakan' => 'reports/default_report.jpg', 
                'status' => collect($statuses)->random(), // Mengambil dari daftar status baru
                'ai_analysis' => 'Hasil analisis AI otomatis untuk laporan ke-' . $i,
                'ai_damage_type' => 'Crack/Lubang',
                'ai_severity' => collect(['Rendah', 'Sedang', 'Tinggi'])->random(),
            ]);

            // 2. Tambahkan 1-3 foto ke tabel report_images (Relasi Many)
            $jumlahFoto = rand(1, 3);
            for ($j = 1; $j <= $jumlahFoto; $j++) {
                ReportImage::create([
                    'report_id' => $report->id,
                    'path' => 'reports/dummy_img_' . $i . '_' . $j . '.jpg',
                ]);
            }
        }
    }
}