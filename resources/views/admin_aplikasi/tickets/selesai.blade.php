@extends('layouts.admin_aplikasi')
@section('title', 'Tiket Selesai')
@section('content')

<div class="px-6 py-4">
    <!-- Judul dan Form Pencarian -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Tiket Selesai - {{ ucfirst($aplikasiName) }}</h1>

        <!-- 🔎 Form pencarian tiket selesai -->
        <form action="{{ route('aplikasi.tickets.selesai') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2">
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

    <!-- 📋 Tabel daftar tiket selesai -->
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
                    <th class="px-4 py-3 text-left">Keluhan</th>
                    <th class="px-4 py-3 text-left">Ruangan</th>
                    <th class="px-4 py-3 text-left">Prioritas</th>
                    <th class="px-4 py-3 text-left">Status</th>
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
<td class="px-4 py-3">
                            @if($ticket->kategoriTickets && $ticket->kategoriTickets->count())
                                {{ $ticket->kategoriTickets->pluck('kategori.nama_kategori')->join(', ') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($ticket->keluhan, 50) }}</td>
                        <td class="px-4 py-3">{{ $ticket->ruangan }}</td>
                        <td class="px-4 py-3">
                            @php
                                $colors = [
                                    'tinggi' => 'bg-red-500',
                                    'sedang' => 'bg-yellow-500',
                                    'rendah' => 'bg-green-500',
                                ];
                            @endphp
                            <span class="text-white text-xs font-semibold px-2 py-1 rounded-full {{ $colors[$ticket->prioritas] ?? 'bg-gray-500' }}">
                                {{ ucfirst($ticket->prioritas) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <!-- ✅ Tombol status selesai tanpa icon -->
                            <button
                                onclick="showHistoriModal({{ Js::encode($ticket->progresses) }})"
                                class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md transition"
                            >
                                {{ ucfirst($ticket->status) }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center px-4 py-6 text-gray-400">
                            Tidak ada tiket <strong>selesai</strong> ditemukan untuk aplikasi ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 🔄 Pagination -->
    <div class="mt-4">
        {{ $tickets->withQueryString()->links() }}
    </div>
</div>

<!-- 🖼️ Modal Histori Progres -->
<div id="historiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 max-w-3xl w-full relative overflow-y-auto max-h-[85vh]">
        <!-- Judul Modal -->
        <h2 class="text-2xl font-bold text-blue-600 mb-4 flex items-center gap-2">
            <i class="bi bi-clock"></i>
            Histori Progres Tiket
        </h2>

        <!-- Konten histori akan dimasukkan lewat JS -->
        <div id="historiContent" class="space-y-4 text-sm text-gray-700"></div>

        <!-- Tombol close -->
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-black text-2xl">&times;</button>
    </div>
</div>

<script>
    // 🚀 Fungsi menampilkan modal dengan histori progres
    function showHistoriModal(progresses) {
        const container = document.getElementById('historiContent');
        container.innerHTML = '';

        if (!progresses || progresses.length === 0) {
            container.innerHTML = '<p class="text-gray-500 italic">Belum ada histori progres.</p>';
        } else {
            progresses.forEach((progress) => {
                const tanggal = new Date(progress.waktu_progres);
                const formattedDate = tanggal.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                const formattedTime = tanggal.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // 💡 Timeline card dengan icon jam bootstrap
                container.innerHTML += `
                    <div class="relative pl-6 border-l-4 border-blue-500 bg-gray-50 rounded-lg shadow-sm p-4">
                        <span class="absolute -left-4 top-4 w-7 h-7 flex items-center justify-center bg-blue-500 text-white rounded-full border-2 border-white">
                            <i class="bi bi-clock text-sm"></i>
                        </span>
                        <div class="flex flex-col">
                            <p class="text-sm text-gray-600"><strong>Ditangani oleh:</strong> ${(progress.admin_aplikasi && progress.admin_aplikasi.name) ? progress.admin_aplikasi.name : 'Tidak diketahui'}</p>
                            <p class="text-sm"><strong>Hari/Tanggal:</strong> ${formattedDate}</p>
                            <p class="text-sm"><strong>Jam:</strong> ${formattedTime}</p>
                            <p class="mt-2 text-gray-800"><strong>Progres:</strong> ${progress.narasi}</p>
                        </div>
                    </div>
                `;
            });
        }

        document.getElementById('historiModal').classList.remove('hidden');
        document.getElementById('historiModal').classList.add('flex');
    }

    // ❌ Fungsi menutup modal
    function closeModal() {
        document.getElementById('historiModal').classList.add('hidden');
        document.getElementById('historiModal').classList.remove('flex');
    }
</script>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
