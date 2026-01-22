@extends('layouts.admin')

@section('title', 'Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- FORM TAMBAH USER --}}
<form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data"
      class="card mb-4">
    @csrf
    <div class="card-body row g-2">

        <div class="col-md-3">
            <input name="nik" class="form-control" placeholder="NIK" required>
        </div>

        <div class="col-md-3">
            <input name="name" class="form-control" placeholder="Nama" required>
        </div>

        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="col-md-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="col-md-2">
            <select name="jenis_kelamin" class="form-control">
                <option value="">JK</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        <div class="col-md-3">
            <input name="pekerjaan" class="form-control" placeholder="Pekerjaan">
        </div>

        <div class="col-md-3">
            <input name="nomor_wa" class="form-control" placeholder="No WhatsApp">
        </div>

        <div class="col-md-2">
            <select name="role" class="form-control" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="col-md-4">
            <input type="file" name="foto_ktp" class="form-control">
        </div>

        <div class="col-md-12">
            <textarea name="alamat" class="form-control" placeholder="Alamat"></textarea>
        </div>

        <div class="col-md-2">
            <button class="btn btn-warning w-100">Tambah</button>
        </div>
    </div>
</form>

{{-- TABEL USER --}}
<div class="card">
    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>WA</th>
                <th>KTP</th>
                <th width="70">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->nomor_wa ?? '-' }}</td>
                <td>
                    @if($user->foto_ktp)
                        <a href="{{ asset('storage/'.$user->foto_ktp) }}" target="_blank">Lihat</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Hapus user ini?')">
                            X
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>

@endsection
