<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ReportsExport;
use App\Models\Report;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanExportController extends Controller
{
    protected array $allowedStatus = [
        'Menunggu',
        'Proses',
        'Selesai',
        'Ditolak'
    ];

    // EXPORT EXCEL
    public function excel($status)
    {
        abort_unless(in_array($status, $this->allowedStatus), 404);

        return Excel::download(
            new ReportsExport($status),
            'laporan-' . strtolower($status) . '.xlsx'
        );
    }

    // EXPORT PDF
    public function pdf($status)
    {
        abort_unless(in_array($status, $this->allowedStatus), 404);

        $reports = Report::with('user')
            ->where('status', $status)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.laporan.export-pdf', [
            'reports' => $reports,
            'status'  => $status,
        ])->setPaper('A4', 'portrait');

        return $pdf->download(
            'laporan-' . strtolower($status) . '.pdf'
        );
    }
}
