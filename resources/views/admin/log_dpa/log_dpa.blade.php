@extends('layouts.admin')

@section('title', 'Riwayat Dana DPA')
@section('page-title', 'Log Penggunaan Dana DPA')

@section('content')
<div class="container-fluid py-2">

    {{-- STATS GRID (Opsi: Menampilkan total penggunaan per kategori) --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="icon-box bg-light-primary text-primary rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Total Transaksi</h6>
                    <h2 class="fw-800 mb-0">{{ $logs->total() }}</h2>
                </div>
            </div>
        </div>
        {{-- Kamu bisa menambahkan stats lain di sini jika diperlukan --}}
    </div>

    {{-- FILTER SECTION --}}
    <div class="mb-4 d-flex flex-wrap gap-3 align-items-center">
        <div class="bg-white p-2 shadow-sm d-inline-flex align-items-center" style="border-radius: 15px;">
            <a class="nav-link px-4 py-2 rounded-3 fw-600 transition-all {{ !request('dpa_id') ? 'bg-dark text-white shadow-sm' : 'text-muted' }}" 
               href="{{ route('admin.logs.dpa') }}"
               style="font-size: 0.85rem; letter-spacing: 0.5px;">
                SEMUA DPA
            </a>
            @foreach ($allDpa as $dpa)
                <a class="nav-link px-4 py-2 rounded-3 fw-600 transition-all {{ request('dpa_id') == $dpa->id ? 'bg-dark text-white shadow-sm' : 'text-muted' }}" 
                   href="{{ route('admin.logs.dpa', ['dpa_id' => $dpa->id]) }}"
                   style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    {{ strtoupper($dpa->nama_rab) }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 24px;">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-800 mb-1 text-dark">Riwayat Transaksi Anggaran</h5>
                            <p class="text-muted small mb-0">Menampilkan mutasi saldo dana DPA secara real-time</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light-subtle border-bottom border-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-600 small">WAKTU & TANGGAL</th>
                                    <th class="py-3 text-muted fw-600 small">KATEGORI DPA</th>
                                    <th class="py-3 text-muted fw-600 small">KETERANGAN</th>
                                    <th class="py-3 text-muted fw-600 small text-end">SALDO AWAL</th>
                                    <th class="py-3 text-muted fw-600 small text-end">NOMINAL</th>
                                    <th class="py-3 text-muted fw-600 small text-end pe-4">SALDO AKHIR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td class="ps-4 py-4">
                                            <div class="fw-bold text-dark mb-0">{{ $log->created_at->format('d M Y') }}</div>
                                            <div class="text-muted small">{{ $log->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-700">
                                                {{ $log->jenisRab->nama_rab }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-dark small fw-600" style="max-width: 300px; white-space: normal;">
                                                {{ $log->keterangan }}
                                            </div>
                                        </td>
                                        <td class="text-end text-muted small">
                                            Rp {{ number_format($log->saldo_awal, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            @if($log->nominal_penggunaan > 0)
                                                <span class="text-danger fw-800">
                                                    - Rp {{ number_format(abs($log->nominal_penggunaan), 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-success fw-800">
                                                    + Rp {{ number_format(abs($log->nominal_penggunaan), 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="fw-800 text-dark">
                                                Rp {{ number_format($log->saldo_akhir, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="opacity-25 mb-3">
                                                <i class="bi bi-journal-x fs-1"></i>
                                            </div>
                                            <p class="text-muted fw-600 small">Belum ada riwayat penggunaan dana.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
    .fw-800 { font-weight: 800; }
    
    .bg-light-primary { background-color: #f0f3ff !important; }
    .bg-light-success { background-color: #e8f5e9 !important; }
    .bg-light-danger { background-color: #fff5f5 !important; }

    .transition-up { transition: all 0.3s ease; }
    .transition-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }

    .transition-all { transition: all 0.2s ease-in-out; }

    .nav-link:hover {
        color: #212529;
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: #fafafa;
    }

    .badge {
        font-size: 0.75rem;
        letter-spacing: 0.3px;
    }
</style>
@endsection