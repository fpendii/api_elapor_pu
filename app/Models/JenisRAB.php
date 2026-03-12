<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisRab extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional jika nama tabel sudah jenis_rabs)
    protected $table = 'jenis_rabs';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_rab',
        'dana',
    ];
}
