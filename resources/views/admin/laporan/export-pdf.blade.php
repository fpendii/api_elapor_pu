<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aduan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        h2 {
            text-align: center;
            margin-bottom: 4px;
        }
        .sub {
            text-align: center;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h2>LAPORAN ADUAN MASYARAKAT</h2>
<div class="sub">
    Status: <strong>{{ strtoupper($status) }}</strong>
</div>

<table>
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Judul</th>
            <th>Pelapor</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $r)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $r->judul }}</td>
            <td>{{ $r->user->name ?? '-' }}</td>
            <td>{{ $r->status }}</td>
            <td>{{ $r->created_at->format('d M Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:15px;">
    Dicetak: {{ now()->format('d M Y H:i') }}
</p>

</body>
</html>
