@extends('layouts.admin')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                {{-- Header Profil dengan Background --}}
                <div class="position-relative" style="height: 120px; background: linear-gradient(90deg, #ffc107, #ffdb70);">
                    <div class="position-absolute top-100 start-0 translate-middle-y ps-4">
                        <div class="bg-white p-1 rounded-circle shadow-sm">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-5 px-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-dark btn-sm px-3 rounded-pill">
                            Edit Profil
                        </a>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <small class="text-muted d-block mb-1">NIK</small>
                                <span class="fw-semibold">{{ $user->nik }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <small class="text-muted d-block mb-1">Status Verifikasi</small>
                                <span class="badge {{ $user->verifikasi == 'sudah' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->verifikasi) }} Terverifikasi
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nomor WhatsApp</small>
                            <p class="fw-medium">{{ $user->nomor_wa ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Pekerjaan</small>
                            <p class="fw-medium">{{ $user->pekerjaan ?? '-' }}</p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Alamat Lengkap</small>
                            <p class="fw-medium">{{ $user->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection