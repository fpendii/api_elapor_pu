@extends('layouts.admin')
@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Profil')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                                style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 600;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-3">{{ strtoupper($user->role) }} • NIK: {{ $user->nik }}</p>
                        <span class="badge {{ $user->verifikasi == 'sudah' ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                            {{ $user->verifikasi == 'sudah' ? 'Terverifikasi' : 'Belum Verifikasi' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">Detail Informasi</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama Lengkap</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Alamat Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nomor WhatsApp</label>
                                    <input type="text" name="nomor_wa" class="form-control"
                                        value="{{ old('nomor_wa', $user->nomor_wa) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control"
                                        value="{{ old('pekerjaan', $user->pekerjaan) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>

                            <hr class="my-4 text-muted">
                            <h6 class="fw-bold mb-3">Keamanan</h6>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Password Baru (Kosongkan jika tidak
                                        ganti)</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
