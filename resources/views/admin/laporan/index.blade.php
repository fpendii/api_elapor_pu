@extends('layouts.admin')

@section('title', 'Daftar Laporan')
@section('page-title', 'Daftar Laporan Aduan')

@section('content')

    @php
        // Konfigurasi Pusat: Warna dan Urutan Status
        $statusConfig = [
            'Proposal' => 'secondary',
            'Verifikasi' => 'info',
            'Penetapan' => 'primary',
            'Pelaksanaan' => 'warning',
            'Pemeriksaan' => 'dark',
            'Selesai' => 'success',
        ];

        $steps = array_keys($statusConfig);
        $currentIndex = array_search($status, $steps);

        // Hitung total "Dalam Proses" (Verifikasi s/d Pemeriksaan)
        $inProgressCount = collect($count)
            ->only(['Verifikasi', 'Penetapan', 'Pelaksanaan', 'Pemeriksaan'])
            ->sum();
    @endphp

    {{-- ===================== --}}
    {{-- RINGKASAN STATUS --}}
    {{-- ===================== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <small class="text-muted d-block mb-1">Proposal Baru</small>
                    <h3 class="fw-bold text-secondary">{{ $count['Proposal'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-info border-4">
                <div class="card-body text-center">
                    <small class="text-muted d-block mb-1">Dalam Proses</small>
                    <h3 class="fw-bold text-info">{{ $inProgressCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-success border-4">
                <div class="card-body text-center">
                    <small class="text-muted d-block mb-1">Selesai</small>
                    <h3 class="fw-bold text-success">{{ $count['Selesai'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body text-center">
                    <small class="opacity-75 d-block mb-1">Total Laporan</small>
                    <h3 class="fw-bold">{{ array_sum($count) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- FILTER & ACTION BAR --}}
    {{-- ===================== --}}
    <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">

        {{-- FILTER --}}
        <div class="dropdown">
            <button class="btn btn-white border shadow-sm dropdown-toggle fw-semibold px-3 py-2" type="button"
                data-bs-toggle="dropdown">
                <i class="fas fa-filter me-1 text-warning"></i>
                Status: {{ $status === 'Semua' ? 'Semua Laporan' : $status }}
            </button>

            <ul class="dropdown-menu shadow border-0">
                <li>
                    <a class="dropdown-item {{ $status === 'Semua' ? 'active' : '' }}"
                        href="{{ route('admin.laporan.index', ['status' => 'Semua']) }}">
                        Semua Laporan
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                @foreach ($statusConfig as $name => $color)
                    <li>
                        <a class="dropdown-item d-flex justify-content-between align-items-center {{ $status === $name ? 'active' : '' }}"
                            href="{{ route('admin.laporan.index', ['status' => $name]) }}">
                            {{ $name }}

                            @if (($count[$name] ?? 0) > 0)
                                <span class="badge bg-danger rounded-pill ms-2">
                                    {{ $count[$name] }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>


        {{-- STEPPER --}}
        <div class="d-flex align-items-center px-3 py-2 bg-white border rounded shadow-sm">

            <span class="badge bg-secondary px-3 py-2" style="font-size:0.9rem">
                Proposal
            </span>

            <span class="mx-2 text-muted fw-bold" style="font-size:1.2rem"><h3>→</h3></span>

            <span class="badge bg-info px-3 py-2" style="font-size:0.9rem">
                Verifikasi
            </span>

            <span class="mx-2 text-muted fw-bold" style="font-size:1.2rem"><h3>→</h3></span>

            <span class="badge bg-primary px-3 py-2" style="font-size:0.9rem">
                Penetapan
            </span>

            <span class="mx-2 text-muted fw-bold" style="font-size:1.2rem"><h3>→</h3></span>

            <span class="badge bg-warning px-3 py-2" style="font-size:0.9rem">
                Pelaksanaan
            </span>

            <span class="mx-2 text-muted fw-bold" style="font-size:1.2rem"><h3>→</h3></span>

            <span class="badge bg-dark px-3 py-2" style="font-size:0.9rem">
                Pemeriksaan
            </span>

            <span class="mx-2 text-muted fw-bold" style="font-size:1.2rem"><h3>→</h3></span>

            <span class="badge bg-success px-3 py-2" style="font-size:0.9rem">
                Selesai
            </span>

        </div>


        {{-- EXPORT (dorong ke kanan) --}}
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('admin.laporan.export.excel', $status) }}" class="btn btn-sm btn-dark px-3 shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Excel
            </a>

            <a href="{{ route('admin.laporan.export.pdf', $status) }}"
                class="btn btn-sm btn-outline-danger px-3 shadow-sm bg-white">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
        </div>

    </div>

    {{-- ===================== --}}
    {{-- TABEL LAPORAN --}}
    {{-- ===================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning fw-semibold text-white py-3">
            <i class="fas fa-list me-1"></i> Data Laporan &mdash; <span class="text-uppercase">{{ $status }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="ps-3 text-center">#</th>
                            <th>Judul Laporan</th>
                            <th>Pelapor</th>
                            <th>Status</th>
                            <th>Tanggal Masuk</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $r)
                            <tr>
                                <td class="text-center ps-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-bold text-dark">{{ $r->judul }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $r->user->name ?? 'Anonim' }}</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">ID:
                                            #{{ $r->user_id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusConfig[$r->status] ?? 'secondary' }}">
                                        {{ strtoupper($r->status) }}
                                    </span>
                                </td>
                                <td>{{ $r->created_at->translatedFormat('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.laporan.show', $r->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-muted opacity-25"></i>
                                        <p class="text-muted">Tidak ada laporan dengan status
                                            <strong>{{ $status }}</strong>
                                        </p>
                                        <a href="{{ route('admin.laporan.index') }}"
                                            class="btn btn-sm btn-link text-decoration-none">Lihat Semua Laporan</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
