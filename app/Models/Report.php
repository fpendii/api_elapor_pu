<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori',
        'judul',
        'lokasi',
        'deskripsi',
        'foto_kerusakan',
        'status',
    ];

    // Relasi: Laporan ini milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu laporan punya banyak komentar (riwayat progress)
    public function comments()
    {
        return $this->hasMany(ReportComment::class)->orderBy('created_at', 'asc');
    }
}
