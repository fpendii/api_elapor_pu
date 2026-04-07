<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SOC (SDA ON CALL)</title>

    {{-- Bootstrap 5.3.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Font: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --soc-yellow: #ffc107;
            --soc-blue: #0d47a1;
            --soc-dark-blue: #0a192f;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            background-image: radial-gradient(#dee2e6 0.5px, transparent 0.5px);
            background-size: 20px 20px;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border: none;
            border-radius: 1.25rem;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--soc-yellow);
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            /* background: var(--soc-yellow); */
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            /* color: var(--soc-dark-blue); */
            font-weight: 800;
            font-size: 1.6rem;
            /* box-shadow: 0 8px 15px rgba(255, 193, 7, 0.3); */
        }

        .system-title {
            color: var(--soc-dark-blue);
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .system-subtitle {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-label {
            font-weight: 600;
            color: var(--soc-dark-blue);
            font-size: 0.85rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #adb5bd;
        }

        .form-control {
            padding: 0.75rem;
            border-left: none;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .form-control:focus {
            border-color: #dee2e6;
            box-shadow: none;
        }

        .btn-login {
            background-color: var(--soc-yellow);
            border: none;
            padding: 0.8rem;
            font-weight: 700;
            color: var(--soc-dark-blue);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #e5ae06;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }

        .register-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed #dee2e6;
            text-align: center;
        }

        .btn-register {
            color: var(--soc-blue);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .btn-register:hover {
            color: var(--soc-dark-blue);
            text-decoration: underline;
        }

        .error-alert {
            font-size: 0.85rem;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="login-card mx-auto">
            
            <div class="text-center">
                {{-- <div class="brand-logo"></div> --}}
                <img src="/image/logo_pu.png" alt="Logo SDA ON CALL" class="brand-logo">
                <h3 class="system-title">SDA ON CALL</h3>
                <p class="system-subtitle">Portal Pelaporan Internal</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger error-alert mb-4 bg-light" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="user@sdaoncall.com"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                        <input type="password" name="password" id="password" 
                            class="form-control" placeholder="••••••••"
                            required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            Ingat Perangkat
                        </label>
                    </div>
                    {{-- <a href="#" class="text-decoration-none small fw-bold" style="color: var(--soc-blue)">Lupa Password?</a> --}}
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-login text-uppercase">
                        Masuk <i class="bi bi-box-arrow-in-right ms-2"></i>
                    </button>
                </div>
            </form>

            {{-- <div class="register-section">
                <p class="small text-muted mb-0">Belum memiliki akun SOC?</p>
                <a href="{{ url('/register') }}" class="btn-register small">
                    Daftar Akun Baru <i class="bi bi-arrow-right-short"></i>
                </a>
            </div> --}}

            <p class="mt-4 mb-0 text-center text-muted" style="font-size: 0.7rem;">
                &copy; {{ date('Y') }} SDA ON CALL. All Rights Reserved.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>