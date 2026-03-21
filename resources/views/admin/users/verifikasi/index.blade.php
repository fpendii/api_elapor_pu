@extends('layouts.admin')

@section('title', 'Verifikasi User')
@section('page-title', 'Verifikasi Akun Masyarakat')

@section('content')
<div class="container-fluid py-2">

    {{-- STATS GRID --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="icon-box bg-light-warning text-warning rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-exclamation fs-3"></i>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Menunggu</h6>
                    <h2 class="fw-800 mb-0">{{ $count['menunggu'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="icon-box bg-light-success text-success rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-check-fill fs-3"></i>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Terverifikasi (ACC)</h6>
                    <h2 class="fw-800 mb-0">{{ $count['acc'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 transition-up" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="icon-box bg-light-danger text-danger rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-x-fill fs-3"></i>
                    </div>
                    <h6 class="text-muted fw-600 text-uppercase small mb-1">Ditolak</h6>
                    <h2 class="fw-800 mb-0">{{ $count['tidak-acc'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- NAV TAB STYLING --}}
    <div class="mb-4">
        <div class="bg-white p-2 shadow-sm d-inline-flex align-items-center" style="border-radius: 15px;">
            @foreach (['menunggu','acc','tidak-acc'] as $tab)
                <a class="nav-link px-4 py-2 rounded-3 fw-600 transition-all {{ $status === $tab ? 'bg-warning text-dark shadow-sm' : 'text-muted' }}" 
                   href="{{ url('admin/users/verifikasi?status='.$tab) }}"
                   style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    {{ strtoupper($tab) }}
                    @if($tab === 'menunggu' && $count['menunggu'] > 0)
                        <span class="badge bg-danger ms-2 rounded-pill" style="font-size: 0.7rem;">{{ $count['menunggu'] }}</span>
                    @endif
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
                            <h5 class="fw-800 mb-1 text-dark">Daftar Pengguna</h5>
                            <p class="text-muted small mb-0">Status: <span class="text-warning fw-bold">{{ strtoupper($status) }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light-subtle border-bottom border-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-600 small">NAMA PENGGUNA</th>
                                    <th class="py-3 text-muted fw-600 small">EMAIL</th>
                                    <th class="py-3 text-muted fw-600 small">NOMOR IDENTITAS (NIK)</th>
                                    <th class="py-3 text-muted fw-600 small">STATUS VERIFIKASI</th>
                                    <th class="py-3 text-muted fw-600 small text-center pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                    <tr>
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3 bg-light-primary text-primary fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">{{ $u->name }}</div>
                                                    <div class="text-muted small">User ID: #{{ $u->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted small">{{ $u->email }}</td>
                                        <td class="fw-600 text-dark small">{{ $u->nik }}</td>
                                        <td>
                                            @php
                                                $verifColor = [
                                                    'acc' => 'success',
                                                    'menunggu' => 'warning',
                                                    'tidak-acc' => 'danger'
                                                ][$u->verifikasi] ?? 'secondary';
                                            @endphp
                                            <div class="badge-dot d-flex align-items-center gap-2">
                                                <span class="d-inline-block rounded-circle bg-{{ $verifColor }}" style="width: 8px; height: 8px;"></span>
                                                <span class="text-{{ $verifColor }} fw-800 small text-uppercase">{{ $u->verifikasi }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <a href="{{ route('admin.users.verifikasi.show', $u->id) }}" 
                                               class="btn btn-dark btn-sm rounded-3 px-3 fw-600 shadow-sm">
                                                Detail <i class="bi bi-arrow-right-short ms-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="opacity-25 mb-3">
                                                <i class="bi bi-people fs-1"></i>
                                            </div>
                                            <p class="text-muted fw-600 small">Tidak ada data pengguna dalam kategori ini.</p>
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
    .bg-light-warning { background-color: #fff9e6 !important; }
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

    /* Table Hover Styling */
    .table-hover tbody tr:hover {
        background-color: #fafafa;
    }
</style>
@endsection