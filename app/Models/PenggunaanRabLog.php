<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanRabLog extends Model
{
    use HasFactory;

    protected $table = 'penggunaan_rab_logs';
    protected $fillable = ['jenis_rab_id', 'nominal_penggunaan', 'saldo_awal', 'saldo_akhir', 'keterangan'];

    public function jenisRab()
    {
        return $this->belongsTo(JenisRab::class, 'jenis_rab_id');
    }
}

