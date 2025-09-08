@extends('layouts.admin')

@section('title', 'Daftar Subkategori')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">
        Daftar Subkategori untuk Kategori: {{ $kategori->nama_kategori }}
    </h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol tambah --}}
    <div class="flex justify-end items-center mb-6">
        <a href="{{ route('admin.subkategori.create', $kategori->id) }}" 
           class="bg-blue-300 bg-opacity-20 hover:bg-blue-300 hover:bg-opacity-30 text-white px-6 py-2 rounded-md font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            Tambah Subkategori
        </a>
    </div>

    {{-- Table daftar subkategori --}}
    <div class="bg-white shadow rounded-lg p-4">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Nama Subkategori</th>
                    <th class="border px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subkategoris as $index => $subkategori)
                    <tr>
                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border px-4 py-2">{{ $subkategori->nama_subkategori }}</td>
                        <td class="border px-4 py-2 text-center">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.subkategori.edit', [$kategori->id, $subkategori->id]) }}" 
                               class="inline-block text-blue-600 hover:text-blue-800 mr-2" 
                               title="Edit">
                                <i class="bi bi-pencil-square text-xl"></i>
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.subkategori.destroy', [$kategori->id, $subkategori->id]) }}" 
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Yakin ingin menghapus subkategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="bi bi-trash-fill text-xl"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">Belum ada subkategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
