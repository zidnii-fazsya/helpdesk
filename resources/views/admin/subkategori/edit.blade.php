@extends('layouts.admin')

@section('content')
<div class="px-8 py-6 max-w-md mx-auto bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-6">Edit Subkategori</h2>

    {{-- Form Update --}}
    <form action="{{ route('admin.subkategori.update', ['kategori' => $kategori->id, 'subkategori' => $subkategori->id]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Pilihan Kategori --}}
        <div class="mb-4">
            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="w-full border border-gray-300 rounded px-3 py-2">
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $subkategori->kategori_id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nama Subkategori --}}
        <div class="mb-4">
            <label for="nama_subkategori" class="block text-sm font-medium text-gray-700 mb-1">Nama Subkategori</label>
            <input 
                type="text" 
                name="nama_subkategori" 
                id="nama_subkategori" 
                value="{{ old('nama_subkategori', $subkategori->nama_subkategori) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 @error('nama_subkategori') border-red-500 @enderror"
                required
            >
            @error('nama_subkategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
            <button type="submit" class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md" style="background-color: #63b3ed;">
                <i class="bi bi-check-circle mr-2"></i>Update Subkategori
            </button>

            {{-- Tombol Batal kembali ke daftar subkategori di kategori terkait --}}
            <a href="{{ route('admin.subkategori.index', $kategori->id) }}" 
               class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-8 rounded-full text-center transition duration-300">
                <i class="bi bi-x-circle mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection
