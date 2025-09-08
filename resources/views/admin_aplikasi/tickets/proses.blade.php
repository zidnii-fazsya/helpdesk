@extends('layouts.admin_aplikasi')

@section('title', 'Tiket Sedang Proses')

@section('content')
<div class="px-6 py-4">
    <!-- Judul dan Form Pencarian -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Tiket Sedang Proses - {{ ucfirst($aplikasiName) }}</h1>

        <form action="{{ route('aplikasi.tickets.proses') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2">
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

    <!-- Tabel Tiket -->
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
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $ticket->reporter_name }}</td>
                        <td class="px-4 py-3">{{ $ticket->jabatan }}</td>
                        <td class="px-4 py-3">{{ $ticket->satuan_kerja ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $ticket->ticket_number }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($ticket->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-3">
                            @if($ticket->kategoriTickets && $ticket->kategoriTickets->count())
                                {{ $ticket->kategoriTickets->pluck('kategori.nama_kategori')->join(', ') }}
                            @else
                                -
                            @endif
                        </td>
                        <!-- Kolom Prioritas dengan warna label -->
                        <td class="px-4 py-3">
                            @if($ticket->prioritas == 'tinggi')
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Tinggi</span>
                            @elseif($ticket->prioritas == 'sedang')
                                <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs font-semibold">Sedang</span>
                            @else
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Rendah</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($ticket->keluhan, 50) }}</td>
                        <td class="px-4 py-3">{{ $ticket->ruangan }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('aplikasi.tickets.progress.form', $ticket->id) }}"
                               class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold px-3 py-1 rounded-full transition">
                                {{ $ticket->status }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center px-4 py-6 text-gray-400">
                            Tidak ada tiket dengan status <strong>Proses</strong> ditemukan untuk aplikasi ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tickets->withQueryString()->links() }}
    </div>
</div>
@endsection
