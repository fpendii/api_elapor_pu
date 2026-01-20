<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->pluck('id');

        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create([
                'role' => 'user',
                'verifikasi' => true
            ])->pluck('id');
        }

        $kategori = ['Jalan Rusak', 'Lampu Jalan', 'Sampah', 'Banjir', 'Fasilitas Umum'];

        for ($i = 1; $i <= 50; $i++) {
            Report::create([
                'user_id' => $users->random(),
                'kategori' => $kategori[array_rand($kategori)],
                'judul' => 'Laporan #' . $i,
                'lokasi' => 'Kelurahan Contoh RT ' . rand(1, 10),
                'deskripsi' => 'Ini adalah laporan kerusakan ke-' . $i,
                'foto_kerusakan' => 'test_image_' . $i,
                'status' => collect(['Menunggu', 'Proses', 'Selesai', 'Ditolak'])->random(),
            ]);
        }
    }
}
