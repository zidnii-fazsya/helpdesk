@extends('layouts.admin')

@section('title', 'Tambah Subkategori')

@section('content')
<div class="px-8 py-6 max-w-md mx-auto bg-white rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-6">
        Tambah Subkategori 
        @isset($kategori)
            untuk Kategori: <span class="text-blue-600 font-bold">{{ $kategori->nama_kategori }}</span>
        @endisset
    </h2>

    {{-- Form Simpan Subkategori --}}
    <form 
        action="{{ route('admin.subkategori.store', ['kategori' => $kategori->id]) }}" 
        method="POST"
    >
        @csrf

        {{-- Input nama subkategori --}}
        <div class="mb-4">
            <label for="nama_subkategori" class="block text-sm font-medium text-gray-700 mb-1">Nama Subkategori</label>
            <input 
                type="text" 
                name="nama_subkategori" 
                id="nama_subkategori"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('nama_subkategori') border-red-500 @enderror"
                value="{{ old('nama_subkategori') }}" 
                placeholder="Masukkan nama subkategori"
                required
            >
            @error('nama_subkategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
            <button 
                type="submit"
                class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md"
                style="background-color: #4299e1;"
            >
                <i class="bi bi-check-circle mr-2"></i> Simpan Subkategori
            </button>
            <a 
                href="{{ route('admin.kategori.index') }}"
                class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-semibold py-3 px-8 rounded-full text-center transition duration-300 flex items-center justify-center"
            >
                <i class="bi bi-x-circle mr-2"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection
