<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function collection()
    {
        return Report::with('user')
            ->where('status', $this->status)
            ->select(
                'judul',
                'kategori',
                'lokasi',
                'status',
                'created_at',
                'user_id'
            )
            ->get()
            ->map(function ($r) {
                return [
                    $r->judul,
                    $r->kategori,
                    $r->lokasi,
                    $r->status,
                    $r->created_at->format('d-m-Y'),
                    $r->user->name ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Judul Laporan',
            'Kategori',
            'Lokasi',
            'Status',
            'Tanggal Lapor',
            'Nama Pelapor',
        ];
    }
}
