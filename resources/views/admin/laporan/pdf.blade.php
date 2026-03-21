<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }
        /* Header Section */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #ffc107; /* Kuning SOC */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .brand-name {
            color: #0d47a1; /* Biru SOC */
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .report-title {
            font-size: 14px;
            text-transform: uppercase;
            margin: 5px 0;
            font-weight: bold;
        }
        /* Table Content */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th {
            background-color: #0d47a1;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #0a192f;
            text-transform: uppercase;
            font-size: 10px;
        }
        table.data-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        
        /* Zebra Striping */
        table.data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* Status Styling */
        .status-box {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            color: #0d47a1;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="70%">
                <h1 class="brand-name">SDA ON CALL (SOC)</h1>
                <div class="report-title">Rekapitulasi Laporan Aduan</div>
                <div>Status: <strong>{{ strtoupper($status) }}</strong></div>
            </td>
            <td width="30%" style="text-align: right;">
                <div style="font-size: 10px;">Tanggal Cetak:</div>
                <div style="font-weight: bold;">{{ date('d F Y') }}</div>
                <div style="font-size: 10px;">Pukul: {{ date('H:i') }} WIB</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">ID Laporan</th>
                <th width="20%">Pelapor</th>
                <th width="30%">Judul Aduan</th>
                <th width="15%" class="text-center">Tanggal</th>
                <th width="15%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $r)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>#LAP-{{ $r->id }}</strong></td>
                    <td>
                        {{ $r->user->name ?? 'Anonim' }}<br>
                        <small style="color: #666;">NIK: {{ $r->user->nik ?? '-' }}</small>
                    </td>
                    <td>{{ $r->judul }}</td>
                    <td class="text-center">{{ $r->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="status-box">{{ $r->status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data laporan ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi SOC pada {{ date('d/m/Y H:i:s') }}
    </div>

</body>
</html>