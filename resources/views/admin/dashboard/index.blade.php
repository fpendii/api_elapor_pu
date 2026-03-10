@extends('layouts.admin')

@section('title', 'Dashboard Masyarakat')
@section('page-title', 'Ringkasan Laporan')

@section('content')
<div class="container-fluid py-2">
    {{-- WELCOME HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-md-flex align-items-center justify-content-between bg-white p-4 shadow-sm border-0" style="border-radius: 20px;">
                <div class="d-flex align-items-center gap-4">
                    <div class="position-relative">
                        <div class="bg-warning text-dark fw-bold d-flex align-items-center justify-content-center shadow" 
                             style="width: 65px; height: 65px; border-radius: 18px; font-size: 1.5rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="position-absolute bottom-0 end-0 badge border border-2 border-white rounded-circle bg-success p-2">
                            <span class="visually-hidden">Online</span>
                        </span>
                    </div>
                    <div>
                        <h3 class="fw-800 text-dark mb-1">Selamat Datang, {{ explode(' ', trim(auth()->user()->name))[0] }}!</h3>
                        <p class="text-muted mb-0 opacity-75">Sistem memantau <span class="text-dark fw-600">{{ $stats['total'] }} laporan</span> Anda hari ini.</p>
                    </div>
                </div>
                {{-- <div class="mt-3 mt-md-0">
                    <a href="{{ url('admin/laporan') }}" class="btn btn-dark btn-md px-4 fw-600" style="border-radius: 12px;">
                        <i class="bi bi-plus-lg me-2"></i>Buat Aduan
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="row g-4 mb-4">
        @php
            $cards = [
                ['Total Laporan', $stats['total'], 'bi-grid-1x2-fill', 'primary', 'bg-light-primary'],
                ['Proposal Baru', $stats['proposal'], 'bi-file-earmark-plus-fill', 'secondary', 'bg-light-secondary'],
                ['Dalam Proses', $stats['proses'], 'bi-arrow-repeat', 'info', 'bg-light-info'],
                ['Tuntas Selesai', $stats['selesai'], 'bi-patch-check-fill', 'success', 'bg-light-success'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box {{ $card[4] }} text-{{ $card[3] }} rounded-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi {{ $card[2] }} fs-4"></i>
                        </div>
                        <span class="badge rounded-pill bg-light text-muted border-0 small px-2">Live</span>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1" style="letter-spacing: 1px;">{{ $card[0] }}</h6>
                    <h2 class="fw-800 mb-0">{{ $card[1] }}</h2>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABLE SECTION --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 24px;">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Aktivitas Terbaru</h5>
                            <p class="text-muted small mb-0">Riwayat 5 laporan terakhir yang Anda kirimkan.</p>
                        </div>
                        <button class="btn btn-light btn-sm rounded-3 px-3">
                            <i class="bi bi-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light-subtle border-bottom border-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-600 small" style="width: 40%;">INFORMASI LAPORAN</th>
                                    <th class="py-3 text-muted fw-600 small">KATEGORI</th>
                                    <th class="py-3 text-muted fw-600 small">WAKTU</th>
                                    <th class="py-3 text-muted fw-600 small pe-4">STATUS PROGRES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_reports as $report)
                                <tr style="cursor: pointer;">
                                    <td class="ps-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 p-2 bg-light rounded-3 d-none d-sm-block">
                                                <i class="bi bi-envelope-paper text-muted fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ Str::limit($report->judul, 40) }}</div>
                                                <div class="text-muted small mt-1"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>{{ Str::limit($report->lokasi ?? 'N/A', 35) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge border text-dark fw-500 bg-white px-3 py-2" style="border-radius: 10px;">
                                            {{ $report->kategori }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $report->created_at->diffForHumans() }}
                                    </td>
                                    <td class="pe-4">
                                        @php
                                            $config = match($report->status) {
                                                'Proposal'    => ['Menunggu', 'secondary', 'bg-secondary'],
                                                'Selesai'     => ['Selesai', 'success', 'bg-success'],
                                                default       => ['Diproses', 'info', 'bg-info'],
                                            };
                                        @endphp
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="badge-dot d-flex align-items-center gap-2 mb-1">
                                                <span class="d-inline-block rounded-circle {{ $config[2] }}" style="width: 8px; height: 8px;"></span>
                                                <span class="text-{{ $config[1] }} fw-800 small text-uppercase">{{ $config[0] }}</span>
                                            </div>
                                            @if(!in_array($report->status, ['Proposal', 'Selesai']))
                                                <span class="text-muted" style="font-size: 0.65rem; font-weight: 600;">{{ strtoupper($report->status) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="empty" width="80" class="opacity-25 mb-3">
                                        <p class="text-muted">Belum ada laporan yang tercatat.</p>
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
    /* Typography */
    .fw-600 { font-weight: 600; }
    .fw-800 { font-weight: 800; }
    
    /* Soft Colors */
    .bg-light-primary { background-color: #f0f3ff; color: #4e73df; }
    .bg-light-secondary { background-color: #f8f9fc; color: #858796; }
    .bg-light-info { background-color: #e3f2fd; color: #36b9cc; }
    .bg-light-success { background-color: #e8f5e9; color: #1cc88a; }

    /* Interaction */
    .transition-up {
        transition: all 0.3s ease;
    }
    .transition-up:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
    }

    /* Table Hover */
    .table-hover tbody tr:hover {
        background-color: #fbfbfb;
    }

    /* Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection