<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'pesan',
        'foto_progress',
    ];

    // Relasi: Komentar ini merujuk ke satu laporan
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Relasi: Komentar ini ditulis oleh satu user (admin/masyarakat)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
