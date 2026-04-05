@extends('layouts.admin')

@section('title', 'Daftar Laporan')
@section('page-title', 'Daftar Laporan Aduan')

@section('content')
<div class="container-fluid py-2">
    @php
    // Konfigurasi Pusat: Warna dan Icon
    $statusConfig = [
        'Proposal' => ['secondary', 'bg-secondary', 'bi-file-earmark-plus'],
        'Verifikasi' => ['info', 'bg-info', 'bi-shield-check'],
        'Penetapan' => ['primary', 'bg-primary', 'bi-pin-angle'],
        'Pelaksanaan' => ['warning', 'bg-warning', 'bi-hammer'],
        'Pemeriksaan' => ['dark', 'bg-dark', 'bi-search'],
        'Selesai' => ['success', 'bg-success', 'bi-patch-check-fill'],
    ];

    $inProgressCount = collect($count)
        ->only(['Verifikasi', 'Penetapan', 'Pelaksanaan', 'Pemeriksaan'])
        ->sum();
    $totalLaporan = array_sum($count);
    @endphp

    {{-- STATS GRID --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-light-primary text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-grid-1x2-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Semua Laporan</h6>
                    <h2 class="fw-800 mb-0">{{ $totalLaporan }}</h2>
                </div>
            </div>
        </div>
        {{-- Card lainnya tetap sama seperti sebelumnya... --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="icon-box bg-light-secondary text-secondary rounded-4 d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-file-earmark-plus-fill fs-4"></i>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Proposal Baru</h6>
                    <h2 class="fw-800 mb-0">{{ $count['Proposal'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="icon-box bg-light-info text-info rounded-4 d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-arrow-repeat fs-4"></i>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Dalam Proses</h6>
                    <h2 class="fw-800 mb-0">{{ $inProgressCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="icon-box bg-light-success text-success rounded-4 d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-patch-check-fill fs-4"></i>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Tuntas Selesai</h6>
                    <h2 class="fw-800 mb-0">{{ $count['Selesai'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- STEPPER & FILTER SECTION (Tetap Sesuai Kode Awal) --}}
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-lg-9">
            <div class="stepper-wrapper bg-white shadow-sm p-3 d-flex align-items-center overflow-auto" style="border-radius: 20px;">
                @foreach ($statusConfig as $name => $cfg)
                <div class="stepper-item d-flex align-items-center {{ $status === $name ? 'is-active' : '' }}">
                    <a href="{{ route('admin.laporan.index', ['status' => $name]) }}" class="text-decoration-none d-flex align-items-center">
                        <div class="step-icon-circle rounded-circle d-flex align-items-center justify-content-center shadow-sm transition-all {{ $status === $name ? 'bg-' . $cfg[0] . ' text-white' : 'bg-light text-muted' }}" style="width: 38px; height: 38px;">
                            <i class="bi {{ $cfg[2] }} fs-6"></i>
                        </div>
                        <div class="ms-3 me-2">
                            <div class="step-label fw-800 small {{ $status === $name ? 'text-dark' : 'text-muted opacity-75' }}" style="letter-spacing: 0.3px; line-height: 1;">
                                {{ $name }}
                            </div>
                            @if (($count[$name] ?? 0) > 0)
                            <span class="badge rounded-pill bg-danger mt-1" style="font-size: 0.6rem;">{{ $count[$name] }}</span>
                            @endif
                        </div>
                    </a>
                    @if (!$loop->last)
                    <div class="step-connector mx-3 d-none d-md-block">
                        <i class="bi bi-chevron-right text-muted opacity-25"></i>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-3 text-lg-end">
            <a href="{{ route('admin.laporan.export.pdf', ['status' => $status]) }}" class="btn btn-white border-0 shadow-sm fw-600 px-4 py-3 w-100 rounded-4 transition-up">
                <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i> Cetak PDF
            </a>
        </div>
    </div>

    {{-- TABLE SECTION (DISESUAIKAN DENGAN DASHBOARD) --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 24px;">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-800 mb-1 text-dark">Data Laporan &mdash; <span class="text-warning">{{ strtoupper($status) }}</span></h5>
                            <p class="text-muted small mb-0">Manajemen data aduan masyarakat masuk ke dalam sistem.</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-3 px-3 dropdown-toggle fw-600" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-filter me-2"></i>Filter Status
                            </button>
                            <ul class="dropdown-menu shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('admin.laporan.index', ['status' => 'Semua']) }}">Semua Laporan</a></li>
                                @foreach ($statusConfig as $name => $cfg)
                                    <li><a class="dropdown-item" href="{{ route('admin.laporan.index', ['status' => $name]) }}">{{ $name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light-subtle border-bottom border-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-600 small" style="width: 35%;">INFORMASI LAPORAN</th>
                                    <th class="py-3 text-muted fw-600 small">PELAPOR & KATEGORI</th>
                                    <th class="py-3 text-muted fw-600 small">WAKTU MASUK</th>
                                    <th class="py-3 text-muted fw-600 small">STATUS PROGRES</th>
                                    <th class="py-3 text-muted fw-600 small text-center pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $r)
                                @php
                                    $cfg = $statusConfig[$r->status] ?? ['secondary', 'bg-secondary', 'bi-record-circle'];
                                    
                                    $priority = match ($r->prioritas ?? 'Rendah') {
                                        'Darurat' => ['danger', 'bi-exclamation-octagon-fill'],
                                        'Tinggi' => ['warning', 'bi-arrow-up-circle-fill'],
                                        'Sedang' => ['primary', 'bi-dash-circle-fill'],
                                        'Rendah' => ['secondary', 'bi-arrow-down-circle-fill'],
                                        default => ['secondary', 'bi-circle'],
                                    };
                                @endphp
                                <tr onclick="window.location='{{ route('admin.laporan.show', $r->id) }}'" style="cursor: pointer;">
                                    <td class="ps-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 p-2 bg-light rounded-3 d-none d-sm-block">
                                                <i class="bi bi-envelope-paper text-muted fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ Str::limit($r->judul, 40) }}</div>
                                                <div class="text-muted small mt-1">
                                                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                                    {{ Str::limit($r->lokasi ?? 'Lokasi tidak spesifik', 35) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-600 text-dark">{{ $r->user->name ?? 'Anonim' }}</span>
                                            <span class="badge border text-muted fw-500 bg-white px-2 py-1 mt-1 align-self-start" style="border-radius: 8px; font-size: 0.65rem;">
                                                {{ strtoupper($r->kategori ?? 'UMUM') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $r->created_at->translatedFormat('d M Y') }}<br>
                                        <span class="opacity-75">{{ $r->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="badge-dot d-flex align-items-center gap-2 mb-1">
                                                <span class="d-inline-block rounded-circle {{ $cfg[1] }}" style="width: 8px; height: 8px;"></span>
                                                <span class="text-{{ $cfg[0] }} fw-800 small text-uppercase">{{ $r->status }}</span>
                                            </div>
                                            @if (isset($r->prioritas))
                                                <span class="badge bg-{{ $priority[0] }}-subtle text-{{ $priority[0] }} px-2 py-1" style="font-size:0.6rem; border-radius: 6px;">
                                                    <i class="bi {{ $priority[1] }} me-1"></i> {{ strtoupper($r->prioritas) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('admin.laporan.show', $r->id) }}" class="btn btn-dark btn-sm rounded-3 px-3 fw-600">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="empty" width="80" class="opacity-25 mb-3">
                                        <p class="text-muted fw-600 small">Data laporan tidak ditemukan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Pagination Footer --}}
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small fw-600">
                            Menampilkan {{ $reports->firstItem() ?? 0 }} - {{ $reports->lastItem() ?? 0 }} dari {{ $reports->total() }} Laporan
                        </div>
                        <div>
                            {{ $reports->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling tambahan agar identik dengan dashboard */
    .fw-600 { font-weight: 600; }
    .fw-800 { font-weight: 800; }
    
    .bg-light-primary { background-color: #f0f3ff !important; }
    .bg-light-secondary { background-color: #f8f9fc !important; }
    .bg-light-info { background-color: #e3f2fd !important; }
    .bg-light-success { background-color: #e8f5e9 !important; }
    
    .transition-up { transition: all 0.3s ease; }
    .transition-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .table-hover tbody tr:hover {
        background-color: #fbfbfb !important;
    }

    /* Custom badge colors for priority subtles */
    .bg-danger-subtle { background-color: #ffe5e5 !important; }
    .bg-warning-subtle { background-color: #fff4e5 !important; }
    .bg-primary-subtle { background-color: #e5f0ff !important; }
    .bg-secondary-subtle { background-color: #f0f0f0 !important; }

    .btn-white { background: #fff; color: #333; }
</style>
@endsection