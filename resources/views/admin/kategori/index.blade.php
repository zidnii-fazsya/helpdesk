@extends('layouts.admin')

@section('content')
<div class="px-8 py-6">
    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header dan Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Daftar Kategori</h2>
        <a href="{{ route('admin.kategori.create') }}"
           class="bg-blue-300 bg-opacity-20 hover:bg-blue-300 hover:bg-opacity-30 text-white px-6 py-2 rounded-md font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            Tambah Kategori
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full table-auto text-sm">
            <thead style="background-color: #63b3ed" class="text-white font-bold">
                <tr>
                    <th class="px-4 py-2 text-center font-semibold">No</th>
                    <th class="px-4 py-2 text-center font-semibold">Nama Kategori</th>
                    <th class="px-4 py-2 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($kategoris as $index => $kategori)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 text-center">{{ $kategori->nama_kategori }}</td>
                        <td class="px-4 py-2 text-center flex justify-center gap-4 items-center">
                            
                            <!-- Tombol Lihat Subkategori -->
                            <a href="{{ route('admin.subkategori.index', $kategori->id) }}"
                               class="text-[#1e40af] hover:text-[#1e3a8a]" title="Lihat Subkategori">
                                <i class="bi bi-eye text-lg"></i>
                            </a>

                            <!-- Tombol Tambah Subkategori -->
                            <a href="{{ route('admin.subkategori.create', $kategori->id) }}"
                               class="text-green-500 hover:text-green-700" title="Tambah Subkategori">
                                <i class="bi bi-plus-circle text-lg"></i>
                            </a>

                            <!-- Edit Icon -->
                            <a href="{{ route('admin.kategori.edit', $kategori->id) }}"
                               class="text-[#1e40af] hover:text-[#1e3a8a]" title="Edit">
                                <i class="bi bi-pencil-square text-lg"></i>
                            </a>

                            <!-- Delete Icon -->
                            <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                    <i class="bi bi-trash-fill text-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center px-4 py-6 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-tags text-4xl mb-2 text-gray-400"></i>
                                <p class="text-lg font-semibold">Belum ada kategori terdaftar</p>
                                <p class="text-sm">Silakan tambahkan kategori terlebih dahulu</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
