@extends('layouts.admin')

@section('title', 'Daftar Laporan')
@section('page-title', 'Daftar Laporan Aduan')

@section('content')
<div class="container-fluid py-2">
    @php
        // Konfigurasi Pusat: Warna dan Icon
        $statusConfig = [
            'Proposal'    => ['secondary', 'bg-light-secondary', 'bi-file-earmark-plus'],
            'Verifikasi'   => ['info', 'bg-light-info', 'bi-shield-check'],
            'Penetapan'    => ['primary', 'bg-light-primary', 'bi-pin-angle'],
            'Pelaksanaan'  => ['warning', 'bg-light-warning', 'bi-hammer'],
            'Pemeriksaan'  => ['dark', 'bg-light-dark', 'bi-search'],
            'Selesai'      => ['success', 'bg-light-success', 'bi-patch-check-fill'],
        ];

        $inProgressCount = collect($count)->only(['Verifikasi', 'Penetapan', 'Pelaksanaan', 'Pemeriksaan'])->sum();
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

    {{-- STEPPER & FILTER SECTION --}}
    <div class="row g-3 mb-4 align-items-center">
        {{-- Stepper Progress --}}
        <div class="col-lg-8">
            <div class="bg-white p-2 shadow-sm d-flex align-items-center justify-content-between overflow-auto" style="border-radius: 15px; white-space: nowrap;">
                @foreach ($statusConfig as $name => $cfg)
                    <div class="d-flex align-items-center mx-2">
                        <span class="badge {{ $cfg[1] }} text-{{ $cfg[0] }} px-3 py-2 fw-600" style="border-radius: 10px; font-size: 0.8rem;">
                            {{ $name }}
                        </span>
                        @if (!$loop->last)
                            <i class="bi bi-chevron-right mx-2 text-muted opacity-50"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Export Buttons --}}
        <div class="col-lg-4 text-lg-end">
            <div class="d-inline-flex gap-2">
                <a href="{{ route('admin.laporan.export.excel', $status) }}" class="btn btn-white border-0 shadow-sm fw-600 px-3 py-2" style="border-radius: 12px;">
                    <i class="bi bi-file-earmark-excel text-success me-1"></i> Excel
                </a>
                <a href="{{ route('admin.laporan.export.pdf', $status) }}" class="btn btn-white border-0 shadow-sm fw-600 px-3 py-2" style="border-radius: 12px;">
                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i> PDF
                </a>
            </div>
        </div>
    </div>

    {{-- FILTER DROPDOWN --}}
    <div class="mb-4">
        <div class="dropdown">
            <button class="btn btn-white border-0 shadow-sm dropdown-toggle fw-600 px-4 py-2" type="button" data-bs-toggle="dropdown" style="border-radius: 12px;">
                <i class="bi bi-funnel me-2 text-warning"></i>
                Status: {{ $status === 'Semua' ? 'Semua Laporan' : $status }}
            </button>
            <ul class="dropdown-menu shadow border-0 p-2" style="border-radius: 15px;">
                <li><a class="dropdown-item rounded-3 {{ $status === 'Semua' ? 'active' : '' }}" href="{{ route('admin.laporan.index', ['status' => 'Semua']) }}">Semua Laporan</a></li>
                <li><hr class="dropdown-divider"></li>
                @foreach ($statusConfig as $name => $cfg)
                    <li>
                        <a class="dropdown-item d-flex justify-content-between align-items-center rounded-3 {{ $status === $name ? 'active' : '' }}" href="{{ route('admin.laporan.index', ['status' => $name]) }}">
                            {{ $name }}
                            @if (($count[$name] ?? 0) > 0)
                                <span class="badge bg-danger rounded-pill small">{{ $count[$name] }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 24px;">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-800 mb-1 text-dark">Data Laporan &mdash; <span class="text-warning">{{ strtoupper($status) }}</span></h5>
                    <p class="text-muted small mb-0">Manajemen data aduan masyarakat masuk ke dalam sistem.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light-subtle border-bottom border-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-600 small">INFORMASI LAPORAN</th>
                                    <th class="py-3 text-muted fw-600 small">PELAPOR</th>
                                    <th class="py-3 text-muted fw-600 small">TANGGAL MASUK</th>
                                    <th class="py-3 text-muted fw-600 small">STATUS</th>
                                    <th class="py-3 text-muted fw-600 small text-center pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $r)
                                    <tr>
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 p-2 bg-light rounded-3">
                                                    <i class="bi bi-file-text text-muted fs-5"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">{{ Str::limit($r->judul, 45) }}</div>
                                                    <div class="text-muted small mt-1">#LAP-{{ $r->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-600 text-dark">{{ $r->user->name ?? 'Anonim' }}</span>
                                                <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $r->user_id }}</small>
                                            </div>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $r->created_at->translatedFormat('d M Y') }}
                                        </td>
                                        <td>
                                            @php $currentCfg = $statusConfig[$r->status] ?? ['secondary', 'bg-light-secondary']; @endphp
                                            <div class="badge-dot d-flex align-items-center gap-2">
                                                <span class="d-inline-block rounded-circle bg-{{ $currentCfg[0] }}" style="width: 8px; height: 8px;"></span>
                                                <span class="text-{{ $currentCfg[0] }} fw-800 small text-uppercase">{{ $r->status }}</span>
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
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="empty" width="60" class="opacity-25 mb-3">
                                            <p class="text-muted fw-600 small">Data laporan tidak ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-600 { font-weight: 600; }
    .fw-800 { font-weight: 800; }
    
    .bg-light-primary { background-color: #f0f3ff !important; }
    .bg-light-secondary { background-color: #f8f9fc !important; }
    .bg-light-info { background-color: #e3f2fd !important; }
    .bg-light-success { background-color: #e8f5e9 !important; }
    .bg-light-warning { background-color: #fff9e6 !important; }
    .bg-light-dark { background-color: #f1f1f1 !important; }

    .transition-up { transition: all 0.3s ease; }
    .transition-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }

    .btn-white { background: #fff; color: #333; }
    
    /* Smooth Scrollbar for Stepper */
    .overflow-auto::-webkit-scrollbar { height: 4px; }
    .overflow-auto::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection