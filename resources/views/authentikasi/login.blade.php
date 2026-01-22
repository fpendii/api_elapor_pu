<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aplikasi Pelaporan</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Font: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border: none;
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(180deg, #ffc107, #ffca2c);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #212529;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #4b5563;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
        }

        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }

        .btn-login {
            background: linear-gradient(180deg, #ffc107, #ffca2c);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            color: #212529;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(180deg, #e5ae06, #f7ba05);
            transform: translateY(-1px);
        }

        .error-alert {
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="login-card mx-auto">
            <div class="brand-logo">
                P
            </div>

            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1">Selamat Datang</h4>
                <p class="text-muted small">Silakan masuk ke akun Pelaporan Anda</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger error-alert border-0 py-2" role="alert">
                    <ul class="mb-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com"
                        value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label">Password</label>
                        {{-- Opsional: Link Lupa Password --}}
                        {{-- <a href="#" class="text-decoration-none small text-warning fw-medium">Lupa Password?</a> --}}
                    </div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••"
                        required>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-muted" for="remember">
                        Ingat perangkat ini
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-login">
                        Masuk Sekarang
                    </button>
                </div>
            </form>

            {{-- <div class="text-center mt-4">
            <p class="small text-muted mb-0">Belum punya akun? <a href="{{ url('/register') }}" class="text-decoration-none fw-semibold text-dark">Daftar</a></p>
        </div> --}}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
