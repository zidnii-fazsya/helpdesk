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
        <h2 class="text-2xl font-semibold text-gray-800">Daftar Aplikasi</h2>
        <a href="{{ route('admin.aplikasi.create') }}"
           class="bg-blue-300 bg-opacity-20 hover:bg-blue-300 hover:bg-opacity-30 text-white px-6 py-2 rounded-md font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            Tambah Aplikasi
        </a>
    </div>

    <!-- Form Pencarian berdasarkan Nama Aplikasi -->
    <form method="GET" action="{{ route('admin.aplikasi.index') }}" class="mb-6 flex flex-wrap gap-4 items-center">
        <input type="text" name="nama_aplikasi" value="{{ request('nama_aplikasi') }}"
               placeholder="Cari nama aplikasi..."
               class="border border-gray-300 rounded px-4 py-2" />

        <button type="submit"
            class="px-4 py-2 rounded text-white transition"
            style="background-color: #63b3ed;"
            onmouseover="this.style.backgroundColor='#4299e1'"
            onmouseout="this.style.backgroundColor='#63b3ed'">
            <i class="bi bi-search"></i> Cari
        </button>

        @if(request('nama_aplikasi'))
            <a href="{{ route('admin.aplikasi.index') }}"
               class="px-4 py-2 rounded text-gray-800 bg-gray-300 hover:bg-gray-400 flex items-center gap-2">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        @endif
    </form>

    <!-- Tabel Aplikasi -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full table-auto text-sm">
            <thead style="background-color: #63b3ed" class="text-white font-bold">
                <tr>
                    <th class="px-4 py-2 font-semibold">No</th>
                    <th class="px-4 py-2 font-semibold">Nama Aplikasi</th>
                    <th class="px-4 py-2 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($aplikasis as $index => $aplikasi)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $aplikasi->nama_aplikasi }}</td>
                        <td class="px-4 py-2 text-center flex justify-center gap-4">
                            <!-- Edit Icon -->
                            <a href="{{ route('admin.aplikasi.edit', $aplikasi->id) }}"
                               class="text-blue-500 hover:text-blue-700" title="Edit">
                                <i class="bi bi-pencil-square text-lg"></i>
                            </a>

                            <!-- Delete Icon -->
                            <form action="{{ route('admin.aplikasi.destroy', $aplikasi->id) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Yakin ingin menghapus aplikasi ini?')">
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
                                <i class="bi bi-box text-4xl mb-2 text-gray-400"></i>
                                <p class="text-lg font-semibold">Belum ada aplikasi terdaftar</p>
                                <p class="text-sm">Silakan tambahkan aplikasi terlebih dahulu</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
