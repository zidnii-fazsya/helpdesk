<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Helpdesk KEMLU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: #f1f5f9;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .left-panel {
            width: 50%;
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .right-panel {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: #ffffff;
        }

        .logo-image {
            width: 180px;
            max-height: 200px;
            object-fit: contain;
            margin: 1rem 0;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .subtitle {
            font-size: 18px;
            font-weight: 500;
            margin-top: 1rem;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .form-title {
            font-size: 26px;
            font-weight: 600;
            color: #3b82f6;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #3b82f6;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            font-size: 15px;
            border: 2px solid #bfdbfe;
            border-radius: 8px;
            background-color: #eff6ff;
            transition: 0.3s;
        }

        .form-input:focus {
            border-color: #3b82f6;
            outline: none;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .error-message {
            color: red;
            font-size: 13px;
            margin-top: 4px;
        }

        .submit-btn {
            width: 100%;
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #2563eb;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 14px;
        }

        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Panel Kiri -->
    <div class="left-panel">
        <div class="title">Helpdesk KEMLU - Layanan Cepat & Terpadu</div>
        <img src="{{ asset('images/logo.png') }}" alt="Logo KEMLU" class="logo-image">
        <div class="subtitle">Kementerian Luar Negeri Republik Indonesia</div>
    </div>

    <!-- Panel Kanan -->
    <div class="right-panel">
        <div class="form-container">
            <h2 class="form-title">Masuk</h2>

            @if ($errors->has('email'))
                <div class="error-message" style="text-align:center; margin-bottom:1rem;">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope-fill"></i>
                        <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="password" id="password" class="form-input" required>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Login --}}
                <button type="submit" class="submit-btn">Masuk</button>
            </form>

            {{-- Link ke Register --}}
            <div class="register-link">
                Belum punya akun?
                <a href="{{ route('register.admin.helpdesk.form') }}">Register di sini</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>