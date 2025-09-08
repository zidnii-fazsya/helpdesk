@extends('layouts.admin')

@section('content')
<div class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="bg-white p-6 rounded-xl shadow-md max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Aplikasi</h2>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800 border border-green-300 flex items-center">
                <i class="bi bi-check-circle-fill mr-2 text-lg"></i>{{ session('success') }}
            </div>
        @endif

        {{-- Form Tambah Aplikasi --}}
        <form action="{{ route('admin.aplikasi.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Input Nama Aplikasi --}}
            <div>
                <label for="nama_aplikasi" class="block text-sm font-semibold text-gray-700 mb-1">
                    Nama Aplikasi
                </label>
                <input
                    type="text"
                    name="nama_aplikasi"
                    id="nama_aplikasi"
                    class="w-full border border-gray-400 rounded-md px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
                    value="{{ old('nama_aplikasi') }}"
                    required
                >
                @error('nama_aplikasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col gap-4 mt-8"> <!-- Tambah jarak lebih besar di sini -->
                <button type="submit"
                    class="w-full text-white font-semibold py-3 px-6 rounded-full shadow transition-all duration-300"
                    style="background-color: #4299e1;">
                    <i class="bi bi-check-circle mr-2"></i> Simpan Aplikasi
                </button>

                <a href="{{ route('admin.aplikasi.index') }}"
                    class="w-full border border-gray-600 text-black font-semibold py-3 px-6 rounded-full text-center hover:bg-gray-100 transition-all duration-300">
                    <i class="bi bi-x-circle mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
