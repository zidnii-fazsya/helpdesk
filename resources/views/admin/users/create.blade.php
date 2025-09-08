@extends('layouts.admin')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pengguna Admin</h2>

        {{-- Error Global --}}
        @if($errors->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first('error') }}
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- NIP --}}
            <div class="mb-4">
                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nip') border-red-500 @enderror" 
                    required>
                @error('nip')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                    required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" 
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" 
                    required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required>
            </div>

            {{-- Role --}}
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror" 
                    required>
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                    <option value="admin helpdesk" {{ old('role') == 'admin helpdesk' ? 'selected' : '' }}>Admin Helpdesk</option>
                    <option value="admin aplikasi" {{ old('role') == 'admin aplikasi' ? 'selected' : '' }}>Admin Aplikasi</option>
                    <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    <option value="master_helpdesk" {{ old('role') == 'master_helpdesk' ? 'selected' : '' }}>Master Helpdesk</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dropdown Aplikasi (tampil hanya jika role adalah "admin aplikasi") --}}
            <div id="aplikasi-container" class="mb-4" style="display: {{ old('role') == 'admin aplikasi' ? 'block' : 'none' }};">
                <label class="block font-medium text-sm text-gray-700">Kategori Aplikasi <span class="text-red-500">*</span></label>
                <select name="aplikasi_id" id="aplikasi_id" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('aplikasi_id') border-red-500 @enderror">
                    <option value="" disabled {{ old('aplikasi_id') ? '' : 'selected' }}>Pilih Aplikasi</option>
                    @if(isset($aplikasis) && count($aplikasis) > 0)
                        @foreach ($aplikasis as $aplikasi)
                            <option value="{{ $aplikasi->id }}" {{ old('aplikasi_id') == $aplikasi->id ? 'selected' : '' }}>
                                {{ $aplikasi->nama_aplikasi }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>Tidak ada aplikasi tersedia</option>
                    @endif
                </select>
                @error('aplikasi_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Wajib dipilih untuk role Admin Aplikasi</p>
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                <button type="submit"
                    class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md hover:shadow-lg"
                    style="background-color: #4299e1;">
                    <i class="bi bi-check-circle mr-2"></i>Simpan Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-8 rounded-full text-center transition duration-300">
                    <i class="bi bi-x-circle mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Script untuk toggle dropdown aplikasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const aplikasiContainer = document.getElementById('aplikasi-container');
            const aplikasiSelect = document.getElementById('aplikasi_id');

            function toggleAplikasiDropdown() {
                if (roleSelect.value === 'admin aplikasi') {
                    aplikasiContainer.style.display = 'block';
                    aplikasiSelect.setAttribute('required', 'required');
                } else {
                    aplikasiContainer.style.display = 'none';
                    aplikasiSelect.removeAttribute('required');
                    aplikasiSelect.value = ''; // Clear selection
                }
            }

            roleSelect.addEventListener('change', toggleAplikasiDropdown);

            // Inisialisasi saat halaman dimuat
            toggleAplikasiDropdown();
        });
    </script>
@endsection