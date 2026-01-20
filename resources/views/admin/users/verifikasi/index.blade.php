@extends('layouts.admin')

@section('title', 'Verifikasi User')
@section('page-title', 'Verifikasi Akun Masyarakat')

@section('content')

{{-- RINGKASAN --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <small class="text-muted">Menunggu</small>
                <h3 class="fw-bold text-warning">{{ $count['menunggu'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <small class="text-muted">ACC</small>
                <h3 class="fw-bold text-success">{{ $count['acc'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <small class="text-muted">Ditolak</small>
                <h3 class="fw-bold text-danger">{{ $count['tidak-acc'] }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- NAV TAB --}}
<ul class="nav nav-tabs mb-3">
    @foreach (['menunggu','acc','tidak-acc'] as $tab)
        <li class="nav-item">
            <a class="nav-link {{ $status === $tab ? 'active fw-semibold' : '' }}"
               href="{{ url('admin/users/verifikasi?status='.$tab) }}">
                {{ strtoupper($tab) }}

                @if($tab === 'menunggu' && $count['menunggu'] > 0)
                    <span class="badge bg-danger ms-1">
                        {{ $count['menunggu'] }}
                    </span>
                @endif
            </a>
        </li>
    @endforeach
</ul>

{{-- TABEL --}}
<div class="card shadow-sm">
    <div class="card-header bg-warning fw-semibold">
        Data User - {{ strtoupper($status) }}
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIK</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->nik }}</td>
                        <td>
                            <span class="badge bg-{{ $u->verifikasi === 'acc' ? 'success' : ($u->verifikasi === 'menunggu' ? 'warning' : 'danger') }}">
                                {{ strtoupper($u->verifikasi) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.verifikasi.show', $u->id) }}"
                               class="btn btn-sm btn-outline-dark">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
