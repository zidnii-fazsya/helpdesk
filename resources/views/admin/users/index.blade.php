@extends('layouts.admin')

@section('content')
<div class="px-8 py-6">
    <!-- Header dan Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Daftar Admin Helpdesk</h2>
        <a href="{{ route('admin.users.create') }}"
           class="bg-blue-300 bg-opacity-20 hover:bg-blue-300 hover:bg-opacity-30 text-white px-6 py-2 rounded-md font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            Tambah Pengguna
        </a>
    </div>

    <!-- Form Pencarian berdasarkan Nama -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 flex flex-wrap gap-4 items-center">
        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari berdasarkan Nama"
               class="border border-gray-300 rounded px-4 py-2" />

        <button type="submit"
            class="px-4 py-2 rounded text-white transition"
            style="background-color: #63b3ed;"
            onmouseover="this.style.backgroundColor='#4299e1'"
            onmouseout="this.style.backgroundColor='#63b3ed'">
            Cari
        </button>

        @if(request('nama'))
            <a href="{{ route('admin.users.index') }}" class="text-sm text-red-500 underline">Reset</a>
        @endif
    </form>

    <!-- Tabel Daftar User -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full table-auto text-sm">
            <thead style="background-color: #63b3ed" class="text-white font-bold">
                <tr>
                    <th class="px-4 py-2 font-semibold">No</th>
                    <th class="px-4 py-2 font-semibold">NIP</th>
                    <th class="px-4 py-2 font-semibold">Nama</th>
                    <th class="px-4 py-2 font-semibold">Aplikasi</th>
                    <th class="px-4 py-2 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($users as $index => $user)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $user->nip }}</td>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">
                            @if ($user->role === 'admin helpdesk')
                                Admin Helpdesk
                            @elseif ($user->role === 'admin aplikasi')
                                {{ $user->nama_aplikasi ?? '-' }}
                            @else
                                {{ ucfirst($user->role) }}
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center flex justify-center gap-4">
                            <!-- Edit (icon pensil biru) -->
                            <a href="{{ route('admin.users.edit', $user->id) }}" title="Edit" class="text-blue-500 hover:text-blue-700">
                                <i class="bi bi-pencil-square text-lg"></i>
                            </a>

                            <!-- Delete (icon sampah merah) -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')" class="inline">
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
                        <td colspan="5" class="text-center px-4 py-6 text-gray-500">
                            Pengguna tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
