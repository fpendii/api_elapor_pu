<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia

        // 1. Buat Akun Admin (Untuk Login Pertama Kali)
        DB::table('users')->insert([
            'nik' => '1234567890123456',
            'name' => 'Administrator Sistem',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'), // Password: password
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'IT Support',
            'alamat' => 'Kantor Pusat PU',
            'nomor_wa' => '081234567890',
            'role' => 'admin',
            'verifikasi' => 'acc',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Buat 10 Akun User Random menggunakan Faker
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'nik' => $faker->unique()->numerify('################'), // 16 digit angka
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'pekerjaan' => $faker->jobTitle,
                'alamat' => $faker->address,
                'nomor_wa' => $faker->phoneNumber,
                'foto_ktp' => null, // Biasanya dikosongkan saat seeding
                'role' => 'user',
                'verifikasi' => $faker->randomElement(['menunggu', 'acc', 'tidak-acc']),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}