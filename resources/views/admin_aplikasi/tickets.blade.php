@extends('layouts.admin_aplikasi')

@section('title', 'Semua Tiket')

@section('content')
<div class="px-6 py-4">
    <!-- Judul dan Form Pencarian -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Semua Tiket</h1>

        <form action="{{ route('aplikasi.tickets') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2">
            <input
                type="text"
                name="nama"
                placeholder="Cari Nama Pelapor"
                value="{{ request('nama') }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
            />

            <input
                type="date"
                name="tanggal"
                value="{{ request('tanggal') }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
            />

            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg transition"
            >
                Cari
            </button>
        </form>
    </div>

    <!-- Tabel Semua Tiket -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto border border-gray-200">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Pelapor</th>
                    <th class="px-4 py-3 text-left">Jabatan</th>
                    <th class="px-4 py-3 text-left">Satuan Kerja</th>
                    <th class="px-4 py-3 text-left">No. Tiket</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Prioritas</th>
                    <th class="px-4 py-3 text-left">Keluhan</th>
                    <th class="px-4 py-3 text-left">Ruangan</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $index => $ticket)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ ($tickets->currentPage() - 1) * $tickets->perPage() + $index + 1 }}
                        </td>
                        <td class="px-4 py-3">{{ $ticket->reporter_name }}</td>
                        <td class="px-4 py-3">{{ $ticket->jabatan }}</td>
                        <td class="px-4 py-3">{{ $ticket->satuan_kerja ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $ticket->ticket_number }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($ticket->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-3">{{ $ticket->kategori }}</td>

                        <!-- Kolom Prioritas -->
                        <td class="px-4 py-3">
                            @if($ticket->prioritas == 'tinggi')
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Tinggi</span>
                            @elseif($ticket->prioritas == 'sedang')
                                <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs font-semibold">Sedang</span>
                            @elseif($ticket->prioritas == 'rendah')
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Rendah</span>
                            @else
                                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($ticket->keluhan, 50) }}</td>
                        <td class="px-4 py-3">{{ $ticket->ruangan }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClass = match($ticket->status) {
                                    'Masuk' => 'bg-blue-100 text-blue-700',
                                    'Proses' => 'bg-yellow-100 text-yellow-700',
                                    'Selesai' => 'bg-green-100 text-green-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="inline-block {{ $statusClass }} px-3 py-1 rounded-full text-sm font-medium">
                                {{ $ticket->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center px-4 py-6 text-gray-400">
                            Tidak ada tiket ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($tickets->hasPages())
        <div class="mt-4">
            {{ $tickets->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
