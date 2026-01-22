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
                <small class="text-muted">Menunggu</small>
                <h3 class="fw-bold text-warning">
                    {{ $count['Menunggu'] }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-muted">Proses</small>
                <h3 class="fw-bold text-info">
                    {{ $count['Proses'] }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-muted">Selesai</small>
                <h3 class="fw-bold text-success">
                    {{ $count['Selesai'] }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-muted">Ditolak</small>
                <h3 class="fw-bold text-danger">
                    {{ $count['Ditolak'] }}
                </h3>
            </div>
        </div>
    </div>

</div>

{{-- ===================== --}}
{{-- NAV TAB --}}
{{-- ===================== --}}
<ul class="nav nav-tabs mb-3 align-items-center justify-content-between">

    {{-- TAB --}}
    <div class="d-flex">
        <li class="nav-item">
            <a class="nav-link {{ $status === 'Semua' ? 'active fw-semibold' : '' }}"
               href="{{ url('admin/laporan?status=Semua') }}">
                Semua
            </a>
        </li>

        @foreach (['Menunggu','Proses','Selesai','Ditolak'] as $tab)
            <li class="nav-item">
                <a class="nav-link {{ $status === $tab ? 'active fw-semibold' : '' }}"
                   href="{{ url('admin/laporan?status='.$tab) }}">
                    {{ $tab }}

                    @if(in_array($tab, ['Menunggu','Proses']) && $count[$tab] > 0)
                        <span class="badge bg-danger ms-1">
                            {{ $count[$tab] }}
                        </span>
                    @endif
                </a>
            </li>
        @endforeach
    </div>

    {{-- EXPORT --}}
    <div class="d-flex gap-2">
    <a href="{{ route('admin.laporan.export.excel', $status) }}"
       class="btn btn-sm btn-dark">
        Export Excel
    </a>

    <a href="{{ route('admin.laporan.export.pdf', $status) }}"
       class="btn btn-sm btn-outline-danger">
        Export PDF
    </a>
</div>


</ul>


{{-- ===================== --}}
{{-- TABEL LAPORAN --}}
{{-- ===================== --}}
<div class="card shadow-sm">

    <div class="card-header bg-warning fw-semibold">
        Data Laporan — {{ $status }}
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Judul</th>
                    <th>Pelapor</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->judul }}</td>
                        <td>{{ $r->user->name ?? '-' }}</td>
                        <td>
                            @php
                                $badge = match($r->status) {
                                    'Menunggu' => 'warning',
                                    'Proses'   => 'info',
                                    'Selesai'  => 'success',
                                    'Ditolak'  => 'danger',
                                    default    => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">
                                {{ $r->status }}
                            </span>
                        </td>
                        <td>{{ $r->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ url('admin/laporan/'.$r->id) }}"
                               class="btn btn-sm btn-outline-dark">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="text-center text-muted py-4">
                            Tidak ada data laporan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
