<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Pelaporan Masyarakat</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8;
            padding: 40px 0;
        }

        .register-card {
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #ffc107, #ffca2c);
            padding: 2rem;
            text-align: center;
            color: #212529;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .form-control, .form-select {
            padding: 0.6rem 0.75rem;
            border-radius: 0.5rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
        }

        .btn-register {
            background: linear-gradient(180deg, #ffc107, #ffca2c);
            border: none;
            padding: 0.8rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-card">
        <div class="register-header">
            <h3 class="fw-bold mb-1">Registrasi Akun</h3>
            <p class="mb-0 opacity-75">Lengkapi data diri Anda untuk mulai melapor</p>
        </div>

        <div class="p-4 p-md-5">
            @if($errors->any())
                <div class="alert alert-danger border-0 mb-4">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-12">
                        <h6 class="section-title">Informasi Identitas</h6>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIK (Sesuai KTP)</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih...</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan') }}" required>
                    </div>

                    <div class="col-12 mt-3">
                        <h6 class="section-title">Kontak & Keamanan</h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" name="nomor_wa" class="form-control" placeholder="0812..." value="{{ old('nomor_wa') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-12 mt-3">
                        <h6 class="section-title">Alamat & Validasi</h6>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label">Unggah Foto KTP</label>
                        <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                        <div class="form-text">Format: JPG, PNG (Maks. 2MB)</div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-2">
                    <button type="submit" class="btn btn-register">Daftar Sekarang</button>
                    <a href="{{ url('/') }}" class="btn btn-link text-decoration-none text-muted small">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>