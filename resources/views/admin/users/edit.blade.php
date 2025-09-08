@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Pengguna</h2>

        @if($errors->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first('error') }}
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- NIP --}}
            <div class="mb-4">
                <label for="nip" class="block text-gray-700 font-semibold mb-2">NIP</label>
                <input type="text" id="nip" name="nip" value="{{ old('nip', $user->nip) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nip') border-red-500 @enderror" required>
                @error('nip')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password
                    <span class="text-sm text-gray-500">(Kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="password" id="password" name="password"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Role --}}
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror" 
                        required onchange="toggleAplikasiField()">
                    <option value="" disabled {{ old('role', $user->role) == '' ? 'selected' : '' }}>Pilih Role</option>
                    <option value="admin helpdesk" {{ old('role', $user->role) == 'admin helpdesk' ? 'selected' : '' }}>Admin Helpdesk</option>
                    <option value="admin aplikasi" {{ old('role', $user->role) == 'admin aplikasi' ? 'selected' : '' }}>Admin Aplikasi</option>
                    <option value="teknisi" {{ old('role', $user->role) == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    <option value="master_helpdesk" {{ old('role', $user->role) == 'master_helpdesk' ? 'selected' : '' }}>Master Helpdesk</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Aplikasi --}}
            <div id="aplikasi-container" class="mb-4" style="display: {{ old('role', $user->role) == 'admin aplikasi' ? 'block' : 'none' }};">
                <label for="aplikasi_id" class="block text-gray-700 font-medium mb-2">Kategori Aplikasi</label>
                <select name="aplikasi_id" id="aplikasi_id"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('aplikasi_id') border-red-500 @enderror">
                    <option value="" disabled {{ old('aplikasi_id', $user->aplikasi_id) == '' ? 'selected' : '' }}>Pilih Aplikasi</option>
                    @foreach($aplikasis as $aplikasi)
                        <option value="{{ $aplikasi->id }}" {{ old('aplikasi_id', $user->aplikasi_id) == $aplikasi->id ? 'selected' : '' }}>
                            {{ $aplikasi->nama_aplikasi }}
                        </option>
                    @endforeach
                </select>
                @error('aplikasi_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan dan Batal --}}
            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                <button type="submit"
                        class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md hover:shadow-lg"
                        style="background-color: #4299e1;">
                    <i class="bi bi-check-circle mr-2"></i>Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-8 rounded-full text-center transition duration-300">
                    <i class="bi bi-x-circle mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAplikasiField() {
        const roleSelect = document.getElementById('role');
        const aplikasiContainer = document.getElementById('aplikasi-container');

        if (roleSelect.value === 'admin aplikasi') {
            aplikasiContainer.style.display = 'block';
        } else {
            aplikasiContainer.style.display = 'none';
        }
    }

    // Panggil saat halaman load
    document.addEventListener("DOMContentLoaded", toggleAplikasiField);
</script>
@endsection
