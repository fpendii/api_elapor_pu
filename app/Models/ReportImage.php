<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'report_images';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'report_id',
        'path',
    ];

    /**
     * Relasi ke tabel Reports (Inverse)
     * Setiap foto dimiliki oleh satu laporan.
     */
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    /**
     * Accessor untuk mendapatkan URL lengkap foto
     * Contoh: $image->full_url akan menghasilkan http://domain.com/storage/reports/nama_file.jpg
     */
    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    // Tambahkan appends agar full_url otomatis muncul saat diubah ke JSON (untuk Flutter)
    protected $appends = ['full_url'];
}
