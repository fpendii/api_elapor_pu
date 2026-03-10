@extends('layouts.admin')

@section('title', 'Daftar Laporan')
@section('page-title', 'Daftar Laporan Aduan')

@section('content')

{{-- ===================== --}}
{{-- RINGKASAN STATUS --}}
{{-- ===================== --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-muted">Proposal Baru</small>
                <h3 class="fw-bold text-secondary">
                    {{ $count['Proposal'] ?? 0 }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-muted">Dalam Proses</small>
                <h3 class="fw-bold text-info">
                    {{ ($count['Verifikasi'] ?? 0) + ($count['Penetapan'] ?? 0) + ($count['Pelaksanaan'] ?? 0) + ($count['Pemeriksaan'] ?? 0) }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-muted">Selesai</small>
                <h3 class="fw-bold text-success">
                    {{ $count['Selesai'] ?? 0 }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-muted">Total Laporan</small>
                <h3 class="fw-bold text-dark">
                    {{ array_sum($count) }}
                </h3>
            </div>
        </div>
    </div>

</div>

{{-- ===================== --}}
{{-- FILTER & ACTION BAR --}}
{{-- ===================== --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    
    {{-- DROPDOWN FILTER --}}
    <div class="dropdown">
        <button class="btn btn-white border shadow-sm dropdown-toggle fw-semibold" type="button" id="filterStatus" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-filter me-1 text-warning"></i> 
            Status: {{ $status === 'Semua' ? 'Semua Laporan' : $status }}
        </button>
        <ul class="dropdown-menu shadow border-0" aria-labelledby="filterStatus">
            <li>
                <a class="dropdown-item {{ $status === 'Semua' ? 'active' : '' }}" href="{{ url('admin/laporan?status=Semua') }}">
                    Semua Laporan
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            @foreach (['Proposal', 'Verifikasi', 'Penetapan', 'Pelaksanaan', 'Pemeriksaan', 'Selesai'] as $item)
                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-center {{ $status === $item ? 'active' : '' }}" href="{{ url('admin/laporan?status='.$item) }}">
                        {{ $item }}
                        @if(isset($count[$item]) && $count[$item] > 0)
                            <span class="badge rounded-pill bg-danger ms-2">{{ $count[$item] }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- EXPORT BUTTONS --}}
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.export.excel', $status) }}" class="btn btn-sm btn-dark px-3">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.laporan.export.pdf', $status) }}" class="btn btn-sm btn-outline-danger px-3">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
    </div>

</div>


{{-- ===================== --}}
{{-- TABEL LAPORAN --}}
{{-- ===================== --}}
<div class="card shadow-sm">

    <div class="card-header bg-warning fw-semibold text-white">
        <i class="fas fa-list me-1"></i> Data Laporan — {{ $status }}
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-3">#</th>
                        <th>Judul</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            <td class="fw-medium text-dark">{{ $r->judul }}</td>
                            <td>{{ $r->user->name ?? '-' }}</td>
                            <td>
                                @php
                                    $badge = match($r->status) {
                                        'Proposal'    => 'secondary',
                                        'Verifikasi'  => 'info',
                                        'Penetapan'   => 'primary',
                                        'Pelaksanaan' => 'warning',
                                        'Pemeriksaan' => 'dark',
                                        'Selesai'     => 'success',
                                        default       => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">
                                    {{ strtoupper($r->status) }}
                                </span>
                            </td>
                            <td>{{ $r->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ url('admin/laporan/'.$r->id) }}"
                                   class="btn btn-sm btn-outline-dark">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                Tidak ada data laporan untuk status <strong>{{ $status }}</strong>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection