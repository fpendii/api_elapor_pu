<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'jenis_kelamin',
        'pekerjaan',
        'alamat',
        'nomor_wa',
        'foto_ktp',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi: Satu user bisa punya banyak laporan
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Relasi: Satu user bisa menulis banyak komentar
    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }
}
