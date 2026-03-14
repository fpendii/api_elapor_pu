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
        'jenis_usulan',
        'jenis_rab_id',

        // AI RESULT
        'ai_analysis',
        'ai_damage_type',
        'ai_severity',

        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisRab()
    {
        return $this->belongsTo(JenisRab::class, 'jenis_rab_id');
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class)
            ->orderBy('created_at', 'asc');
    }

    public function images()
    {
        return $this->hasMany(ReportImage::class, 'report_id');
    }
}
