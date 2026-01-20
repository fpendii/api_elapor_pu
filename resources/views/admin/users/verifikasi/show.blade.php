@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail & Verifikasi User')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-dark text-white">
                Data User
            </div>
            <div class="card-body">
                <p><strong>Nama</strong><br>{{ $user->name }}</p>
                <p><strong>Email</strong><br>{{ $user->email }}</p>
                <p><strong>NIK</strong><br>{{ $user->nik }}</p>
                <p><strong>Alamat</strong><br>{{ $user->alamat }}</p>
                <p><strong>Pekerjaan</strong><br>{{ $user->pekerjaan }}</p>
                <p><strong>No WhatsApp</strong><br>{{ $user->nomor_wa }}</p>

                @if($user->foto_ktp)
                    <p><strong>Foto KTP</strong></p>
                    <img src="{{ asset('storage/'.$user->foto_ktp) }}"
                         class="img-fluid rounded"
                         style="max-width: 300px">
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                Status Verifikasi
            </div>
            <div class="card-body text-center">

                <span class="badge fs-6 mb-3 bg-{{ $user->verifikasi === 'acc' ? 'success' : ($user->verifikasi === 'menunggu' ? 'warning' : 'danger') }}">
                    {{ strtoupper($user->verifikasi) }}
                </span>

                @if($user->verifikasi === 'menunggu')
                    <div class="d-grid gap-2 mt-3">
                        <form method="POST" action="{{ route('admin.users.verifikasi.acc', $user->id) }}">
                            @csrf
                            <button class="btn btn-success">ACC</button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.verifikasi.tolak', $user->id) }}">
                            @csrf
                            <button class="btn btn-danger">Tolak</button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<a href="{{ route('admin.users.verifikasi.index') }}"
   class="btn btn-secondary mt-3">
    ← Kembali
</a>

@endsection
