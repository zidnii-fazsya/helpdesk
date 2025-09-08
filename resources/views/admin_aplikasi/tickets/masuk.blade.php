@extends('layouts.admin_aplikasi')

@section('title', 'Tiket Masuk')

@section('content')
<div class="px-6 py-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Tiket Masuk</h1>

        <!-- Form Pencarian -->
        <form action="{{ route('aplikasi.tickets.masuk') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2">
            <input type="text" name="nama" placeholder="Cari Nama Pelapor" value="{{ request('nama') }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400" />
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400" />
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg transition shadow-md hover:shadow-lg">
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
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $ticket->reporter_name }}</td>
                        <td class="px-4 py-3">{{ $ticket->jabatan }}</td>
                        <td class="px-4 py-3">{{ $ticket->satuan_kerja ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $ticket->ticket_number }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($ticket->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-3">{{ $ticket->kategori }}</td>

                        <!-- Label Prioritas -->
                        <td class="px-4 py-3">
                            @php
                                $prioritasColor = match($ticket->prioritas) {
                                    'tinggi' => 'bg-red-500',
                                    'sedang' => 'bg-yellow-500',
                                    'rendah' => 'bg-green-500',
                                    default => 'bg-gray-400'
                                };
                            @endphp
                            <button
                                onclick="showPrioritasModal({{ Js::encode($ticket->keterangan_prioritas) }}, '{{ ucfirst($ticket->prioritas) }}')"
                                class="px-3 py-1 rounded-full text-white text-xs font-semibold {{ $prioritasColor }} focus:outline-none shadow-md hover:shadow-lg transition">
                                {{ ucfirst($ticket->prioritas) }}
                            </button>
                        </td>

                        <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($ticket->keluhan, 50) }}</td>
                        <td class="px-4 py-3">{{ $ticket->ruangan }}</td>

                        <!-- BAGIAN KONTEN TOMBOL LIHAT DAN AMBIL -->
                        <td class="px-4 py-3">
                            <div class="flex flex-col md:flex-row gap-2">
                                <!-- Tombol Lihat -->
                                <button
                                    onclick="showDetailModal({{ Js::encode($ticket->keluhan) }}, {{ Js::encode($ticket->eskalasi) }}, '{{ ucfirst($ticket->prioritas) }}', {{ Js::encode($ticket->keterangan_prioritas) }})"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow-md text-sm font-medium">
                                    Lihat
                                </button>

                                <!-- Tombol Ambil -->
                                <button
                                    onclick="showAmbilModal('{{ route('aplikasi.tickets.ambil', $ticket->id) }}', {{ Js::encode($ticket->keluhan) }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-md text-sm font-medium">
                                    Ambil
                                </button>
                            </div>
                        </td>
                        <!-- END BAGIAN KONTEN TOMBOL -->
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center px-4 py-6 text-gray-400">
                            Tidak ada tiket masuk ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tickets->withQueryString()->links() }}
    </div>
</div>

<!-- Modal Detail Keluhan + Eskalasi + Prioritas -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <!-- DESAIN ISI KONTEN MODAL: Card, Badge, Shadow -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-2xl p-6 max-w-lg w-full relative">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Detail Tiket</h2>

        <div class="mb-3 p-4 bg-gray-100 rounded-lg shadow-inner">
            <p class="font-semibold text-gray-700">Keluhan:</p>
            <p class="text-gray-600" id="keluhanDetail"></p>
        </div>

        <div class="mb-3 p-4 bg-yellow-50 rounded-lg shadow-inner">
            <p class="font-semibold text-gray-700">Eskalasi:</p>
            <p class="text-gray-600" id="eskalasiDetail"></p>
        </div>

        <div class="mb-3 p-4 bg-red-50 rounded-lg shadow-inner flex justify-between items-center">
            <div>
                <p class="font-semibold text-gray-700">Prioritas:</p>
                <p class="text-gray-600" id="prioritasLabel"></p>
            </div>
            <span class="px-3 py-1 bg-red-200 rounded-full text-red-800 text-xs font-semibold">Info</span>
        </div>

        <div class="mb-3 p-4 bg-gray-50 rounded-lg shadow-inner">
            <p class="font-semibold text-gray-700">Keterangan Prioritas:</p>
            <p class="text-gray-600" id="prioritasKeterangan"></p>
        </div>

        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
    </div>
</div>

<!-- Modal Ambil dengan desain card -->
<div id="ambilModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full relative">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Ambil Tiket</h2>
        <div class="p-4 bg-blue-50 rounded-lg shadow-inner mb-4">
            <p class="text-gray-700" id="keluhanAmbil"></p>
        </div>
        <form id="ambilForm" method="POST">
            @csrf
            <div class="flex justify-end gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow-md">Iya</button>
                <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 shadow-md">Tidak</button>
            </div>
        </form>
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
    </div>
</div>

<script>
    function showDetailModal(keluhan, eskalasi, prioritas, keterangan) {
        document.getElementById('keluhanDetail').textContent = keluhan ?? 'Tidak ada keluhan';
        document.getElementById('eskalasiDetail').textContent = eskalasi ?? 'Tidak ada eskalasi';
        document.getElementById('prioritasLabel').textContent = prioritas ?? '-';
        document.getElementById('prioritasKeterangan').textContent = keterangan ?? 'Tidak ada keterangan prioritas';
        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
    }

    function showAmbilModal(actionUrl, keluhan) {
        document.getElementById('keluhanAmbil').textContent = keluhan ?? 'Tidak ada keluhan';
        const form = document.getElementById('ambilForm');
        form.action = actionUrl;
        document.getElementById('ambilModal').classList.remove('hidden');
        document.getElementById('ambilModal').classList.add('flex');
    }

    function closeModal() {
        document.querySelectorAll('#detailModal, #ambilModal, #prioritasModal').forEach(modal => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }
</script>
@endsection
