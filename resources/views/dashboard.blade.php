@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .status-masuk { background-color: #3b82f6 !important; color: white !important; }
    .status-proses { background-color: #f59e0b !important; color: white !important; }
    .status-selesai { background-color: #10b981 !important; color: white !important; }

    .status-masuk:hover { background-color: #2563eb !important; }
    .status-proses:hover { background-color: #d97706 !important; }
    .status-selesai:hover { background-color: #059669 !important; }

    .prioritas-tinggi { background-color: #dc2626 !important; color: white !important; }
    .prioritas-sedang { background-color: #f59e0b !important; color: white !important; }
    .prioritas-rendah { background-color: #3b82f6 !important; color: white !important; }

    .table-header-blue {
        background-color: #3b82f6 !important;
        color: white !important;
    }

    .table-header-blue th {
        background: transparent !important;
        color: white !important;
    }

    .modal {
        display: none;
    }

    /* Modal styling */
    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 20px;
        width: 100%;
        max-width: 500px;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .modal-header h2 {
        font-size: 18px;
        font-weight: bold;
    }
    .modal-close {
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
    }
    .modal-section {
        margin-bottom: 15px;
        padding: 12px;
        border-radius: 8px;
        background-color: #f9fafb;
    }
    .modal-section h3 {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .bg-yellow { background-color: #fff9db; }
    .bg-red { background-color: #fee2e2; }
    .bg-gray { background-color: #f3f4f6; }

    /* Tombol Lihat */
    .btn-lihat {
        background-color: #16a34a; /* Hijau */
        color: #fff;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease-in-out;
    }
    .btn-lihat:hover {
        background-color: #15803d; /* Hijau lebih gelap */
    }
</style>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="relative text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform" style="background-color: #3b82f6;">
        <i class="bi bi-ticket-perforated-fill text-[5rem] absolute right-3 top-3 opacity-30"></i>
        <div class="relative z-10">
            <p class="text-3xl font-bold">{{ number_format($jumlah_tiket_masuk ?? 0) }}</p>
            <p class="text-sm opacity-90">Total Ticket Masuk</p>
        </div>
    </div>

    <div class="relative text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform" style="background-color: #059669;">
        <i class="bi bi-hourglass-split text-[5rem] absolute right-3 top-3 opacity-30"></i>
        <div class="relative z-10">
            <p class="text-3xl font-bold">{{ number_format($jumlah_tiket_proses ?? 0) }}</p>
            <p class="text-sm opacity-90">Ticket Sedang Proses</p>
        </div>
    </div>

    <div class="relative text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform" style="background-color: #eab308;">
        <i class="bi bi-clipboard2-check text-[5rem] absolute right-3 top-3 opacity-30"></i>
        <div class="relative z-10">
            <p class="text-3xl font-bold">{{ number_format($jumlah_tiket_selesai ?? 0) }}</p>
            <p class="text-sm opacity-90">Ticket Selesai</p>
        </div>
    </div>
</div>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Tiket Terbaru</h2>
    <div class="rounded-lg p-1 shadow-md bg-blue-100">
        <a href="{{ route('admin.tickets.create') }}"
           class="text-white px-6 py-2 rounded-md font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm"
           style="background-color: #3b82f6;"
           onmouseover="this.style.backgroundColor='#2563eb'"
           onmouseout="this.style.backgroundColor='#3b82f6'">
            <i class="bi bi-plus-circle"></i> Tambah Tiket Baru
        </a>
    </div>
</div>

<!-- Tabel Tiket -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-700">
        <h3 class="text-lg font-medium text-white">Tiket Masuk Terbaru</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-800">
            <thead class="table-header-blue">
                <tr class="border-b border-blue-700">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama Pelapor</th>
                    <th class="px-6 py-4">Jabatan</th>
                    <th class="px-6 py-4">Satuan Kerja</th>
                    <th class="px-6 py-4">No. Tiket</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Nama Aplikasi / Subkategori</th>
                    <th class="px-6 py-4">Keluhan</th>
                    <th class="px-6 py-4">Prioritas</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets ?? [] as $index => $ticket)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium">{{ $ticket->reporter_name }}</td>
                        <td class="px-6 py-4">{{ $ticket->jabatan }}</td>
                        <td class="px-6 py-4">{{ $ticket->satuan_kerja }}</td>
                        <td class="px-6 py-4">{{ $ticket->ticket_number }}</td>
                        <td class="px-6 py-4">{{ $ticket->tanggal }}</td>
                        <td class="px-6 py-4">{{ $ticket->kategori }}</td>
                        <td class="px-6 py-4">
                            @if($ticket->kategori === 'Perangkat Lunak')
                                {{ $ticket->aplikasi->nama_aplikasi ?? '-' }}
                            @else
                                {{ $ticket->subkategoris->first()->nama_subkategori ?? '-' }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button class="btn-lihat"
                                onclick="showDetailModal(
                                    '{{ addslashes($ticket->keluhan) }}',
                                    '{{ $ticket->eskalasi ?? 'Tidak ada eskalasi' }}',
                                    '{{ ucfirst($ticket->prioritas) ?? 'Sedang' }}',
                                    '{{ $ticket->keterangan_prioritas ?? 'Tidak ada keterangan prioritas' }}'
                                )">
                                Lihat
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $prioritasClass = match(strtolower($ticket->prioritas ?? '')) {
                                    'tinggi' => 'prioritas-tinggi',
                                    'rendah' => 'prioritas-rendah',
                                    default  => 'prioritas-sedang',
                                };
                            @endphp
                            <span class="text-xs px-3 py-1 font-semibold rounded-full transition {{ $prioritasClass }}">
                                {{ ucfirst($ticket->prioritas) ?? 'Sedang' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = match($ticket->status) {
                                    'Masuk' => 'status-masuk',
                                    'Proses' => 'status-proses',
                                    'Selesai' => 'status-selesai',
                                    default => 'bg-gray-500 text-white',
                                };
                            @endphp
                            <span class="text-xs px-3 py-1 font-semibold rounded-full transition {{ $statusClass }}">
                                {{ $ticket->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center py-12 text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-ticket-perforated-fill text-4xl mb-2"></i>
                                <p class="text-lg font-semibold">Belum ada tiket masuk</p>
                                <p class="text-sm">Tiket yang masuk akan ditampilkan di sini</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Tiket -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center" onclick="closeDetailModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2>Detail Tiket</h2>
            <span class="modal-close" onclick="closeDetailModal()">&times;</span>
        </div>
        <div class="modal-section">
            <h3>Keluhan:</h3>
            <p id="modalKeluhan"></p>
        </div>
        <div class="modal-section bg-yellow">
            <h3>Eskalasi:</h3>
            <p id="modalEskalasi"></p>
        </div>
        <div class="modal-section bg-red">
            <h3>Prioritas:</h3>
            <p id="modalPrioritas"></p>
        </div>
        <div class="modal-section bg-gray">
            <h3>Keterangan Prioritas:</h3>
            <p id="modalKeteranganPrioritas"></p>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="bg-white rounded-xl shadow-lg p-6 mt-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Persentase Tiket Masuk Bulanan</h2>
    <div class="text-center text-gray-400 py-12">
        <div class="flex flex-col items-center">
            <i class="bi bi-bar-chart-line-fill text-4xl mb-2"></i>
            <p class="text-lg font-semibold">Belum ada data untuk grafik</p>
            <p class="text-sm">Grafik akan muncul setelah ada tiket masuk</p>
        </div>
    </div>
</div>

<script>
    function showDetailModal(keluhan, eskalasi, prioritas, keteranganPrioritas) {
        document.getElementById('modalKeluhan').textContent = keluhan;
        document.getElementById('modalEskalasi').textContent = eskalasi;
        document.getElementById('modalPrioritas').textContent = prioritas;
        document.getElementById('modalKeteranganPrioritas').textContent = keteranganPrioritas;
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal(event) {
        if (!event || event.target === document.getElementById('detailModal')) {
            document.getElementById('detailModal').classList.add('hidden');
        }
    }
</script>
@endsection
