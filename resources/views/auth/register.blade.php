<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin Helpdesk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    {{-- Panel Kiri --}}
    <div class="w-1/2 bg-blue-600 flex flex-col items-center justify-center text-white text-center px-8">
        <h1 class="text-2xl font-semibold mb-4">Helpdesk KEMLU - Layanan Cepat & Terpadu</h1>
        <img src="{{ asset('images/logo.png') }}" alt="Logo KEMLU" class="w-40 mb-4">
        <p class="text-lg font-medium">Kementerian Luar Negeri Republik Indonesia</p>
    </div>

    {{-- Panel Kanan --}}
    <div class="w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Daftar Admin Helpdesk</h2>

            {{-- Pesan Success --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Pesan Error --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.admin.helpdesk') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full bg-blue-50 border border-blue-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('name') border-red-500 bg-red-50 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIP & Email --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required
                                class="w-full bg-blue-50 border border-blue-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('nip') border-red-500 bg-red-50 @enderror">
                            @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full bg-blue-50 border border-blue-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('email') border-red-500 bg-red-50 @enderror">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Password & Konfirmasi --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                            <input type="password" name="password" id="password" required
                                class="w-full bg-blue-50 border border-blue-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('password') border-red-500 bg-red-50 @enderror">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full bg-blue-50 border border-blue-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        </div>
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Handphone</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required
                            placeholder="Contoh: 08123456789"
                            class="w-full bg-blue-50 border border-blue-200 rounded px-3 py-2 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 @error('no_hp') border-red-500 bg-red-50 @enderror">
                        @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Hidden Role Field --}}
                    <input type="hidden" name="role" value="admin">

                    {{-- Tombol Submit --}}
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-full transition duration-300 flex items-center justify-center">
                            <i class="bi bi-person-plus mr-2"></i> Daftar sebagai Admin Helpdesk
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah memiliki akun admin helpdesk?
                    <a href="{{ route('login.form') }}" class="text-blue-600 font-semibold hover:underline">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>