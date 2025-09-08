<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin Aplikasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <!-- Panel Kiri -->
    <div class="w-1/2 bg-gradient-to-br from-blue-400 to-blue-600 flex flex-col items-center justify-center text-white px-8 text-center">
        <h1 class="text-2xl font-semibold mb-4">Helpdesk KEMLU - Layanan Cepat & Terpadu</h1>
        <img src="{{ asset('images/logo.png') }}" alt="Logo KEMLU" class="w-40 mb-4">
        <p class="text-lg font-medium">Kementerian Luar Negeri Republik Indonesia</p>
        <div class="mt-8 p-4 bg-white bg-opacity-20 rounded-lg">
            <h3 class="font-semibold">Portal Khusus Admin Aplikasi</h3>
        </div>
    </div>

    <!-- Panel Kanan -->
    <div class="w-1/2 flex items-center justify-center bg-gray-50 px-8">
        <div class="bg-white shadow-lg rounded-xl w-full max-w-md p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-blue-600 mb-1">Login Admin Aplikasi</h2>
                <p class="text-sm text-gray-600">Masuk untuk mengelola tiket aplikasi Anda</p>
            </div>

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 border border-red-200 rounded-lg">
                    <div class="flex items-start gap-2 text-sm">
                        <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
                        <div>
                            @if($errors->count() == 1)
                                {{ $errors->first() }}
                            @else
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.admin.aplikasi.submit') }}" id="loginForm" novalidate>
                @csrf

                <!-- NIP -->
                <div class="mb-4">
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bi bi-person-badge mr-1"></i> NIP
                    </label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required autocomplete="off" autofocus
                           class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-600 @error('nip') border-red-500 bg-red-50 @enderror">
                    @error('nip')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bi bi-gear mr-1"></i> Aplikasi yang Anda Kelola
                    </label>
                    <select name="role" id="role" required
                            class="w-full bg-white border border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-600 @error('role') border-red-500 bg-red-50 @enderror">
                        <option value="">-- Pilih Aplikasi --</option>
                        @forelse ($nama_aplikasi as $aplikasi)
                            @php $roleValue = 'admin aplikasi ' . strtolower($aplikasi->nama_aplikasi); @endphp
                            <option value="{{ $roleValue }}" {{ old('role') == $roleValue ? 'selected' : '' }}>
                                Admin {{ ucwords($aplikasi->nama_aplikasi) }}
                            </option>
                        @empty
                            <option disabled>Belum ada aplikasi tersedia</option>
                        @endforelse
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bi bi-lock mr-1"></i> Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Masukkan password Anda" required autocomplete="current-password"
                               class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-600 @error('password') border-red-500 bg-red-50 @enderror">
                        <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Login -->
                <button type="submit" id="loginBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-full transition duration-300 flex items-center justify-center">
                    <span id="loginBtnText">Masuk</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });

    // Disable button on submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        document.getElementById('loginBtn').disabled = true;
        document.getElementById('loginBtnText').innerText = 'Memproses...';
    });

    // Auto dismiss alert
    setTimeout(() => {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>

</body>
</html>
