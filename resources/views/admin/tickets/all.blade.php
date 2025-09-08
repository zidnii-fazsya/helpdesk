@extends('layouts.admin')

@section('title', 'Semua Tiket')
@section('page-title', 'Semua Tiket')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    [x-cloak] { display: none !important; }

    /* Tombol Lihat */
    .btn-lihat {
        background-color: #22c55e; /* Hijau */
        color: white;
        font-weight: bold;
        padding: 6px 14px;
        border-radius: 8px;
        display: inline-block;
        border: none;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    .btn-lihat:hover {
        background-color: #16a34a;
    }

    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .hidden {
        display: none;
    }

    /* Modal Box */
    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease;
    }
    .modal-content h3 {
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 18px;
        color: #111827;
    }
    .modal-text {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 8px;
        background-color: #f3f4f6; /* default abu */
        font-size: 14px;
    }
    .bg-yellow { background-color: #fef9c3; } /* kuning */
    .bg-red { background-color: #fee2e2; } /* merah */
    .bg-gray { background-color: #f3f4f6; } /* abu */
    .modal-footer {
        text-align: right;
        margin-top: 15px;
    }

    /* Tombol Tutup */
    .btn-tutup {
        background-color: #ef4444; /* Merah */
        color: white;
        padding: 6px 14px;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-tutup:hover {
        background-color: #dc2626;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
@if(session('success'))
<div class="bg-green-100 text-green-800 px-4 py-3 mb-4 rounded-lg border border-green-300">
    <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
</div>
@endif

<!-- Form Pencarian -->
<form method="GET" class="mb-4 flex flex-wrap justify-end items-center gap-3">
    <div class="relative w-full md:w-1/3">
        <input type="text" name="search" placeholder="Cari nama, nomor tiket, atau keluhan"
               class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded focus:ring focus:outline-none"
               value="{{ request('search') }}">
    </div>

    <button type="submit"
            style="background-color: #3b82f6 !important;"
            onmouseover="this.style.backgroundColor='#2563eb'"
            onmouseout="this.style.backgroundColor='#3b82f6'"
            class="text-white font-semibold px-4 py-2 rounded shadow transition">
        Cari
    </button>
</form>

<!-- Tabel Tiket -->
<div class="overflow-hidden rounded-xl shadow bg-white">
    <table class="w-full text-sm text-gray-800">
        <thead style="background-color: #3b82f6;" class="text-white">
            <tr>
                <th class="p-4">No</th>
                <th class="p-4">Tanggal</th>
                <th class="p-4">No. Tiket</th>
                <th class="p-4">Nama Pelapor</th>
                <th class="p-4">Jabatan</th>
                <th class="p-4">Satuan Kerja</th>
                <th class="p-4">Ruangan</th>
                <th class="p-4">Nama Aplikasi / Kategori</th>
                <th class="p-4">Prioritas</th>
                <th class="p-4">Detail</th>
                <th class="p-4">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($tickets as $index => $ticket)
            <tr class="hover:bg-gray-50">
                <td class="p-4">{{ $index + 1 }}</td>
                <td class="p-4">{{ \Carbon\Carbon::parse($ticket->tanggal)->format('d-m-Y') }}</td>
                <td class="p-4 font-mono">{{ $ticket->ticket_number }}</td>
                <td class="p-4 font-semibold">{{ $ticket->reporter_name }}</td>
                <td class="p-4">{{ $ticket->jabatan }}</td>
                <td class="p-4">{{ $ticket->satuan_kerja }}</td>
                <td class="p-4">{{ $ticket->ruangan }}</td>
                <td class="p-4">
                    @if($ticket->aplikasi && $ticket->aplikasi->nama_aplikasi)
                        {{ $ticket->aplikasi->nama_aplikasi }}
                    @elseif(!empty($ticket->kategori) || !empty($ticket->subkategori))
                        {{ $ticket->kategori ?? '' }} {{ $ticket->subkategori ? ' - '.$ticket->subkategori : '' }}
                    @else
                        -
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    @php
                        $prioritasColor = match(strtolower($ticket->prioritas)) {
                            'tinggi' => 'bg-red-500',
                            'sedang' => 'bg-yellow-500',
                            'rendah' => 'bg-green-500',
                            default => 'bg-gray-400'
                        };
                    @endphp
                    <span class="text-white text-xs font-semibold px-2 py-1 rounded {{ $prioritasColor }}">
                        {{ ucfirst($ticket->prioritas) }}
                    </span>
                </td>
                <td class="p-4 text-center">
                    <button class="btn-lihat"
                        onclick="showDetailModal(
                            `{!! nl2br(e($ticket->keluhan)) !!}`,
                            `{{ addslashes($ticket->eskalasi ?? '-') }}`,
                            `{{ ucfirst($ticket->prioritas ?? '-') }}`,
                            `{{ addslashes($ticket->keterangan_prioritas ?? '-') }}`
                        )">
                        Lihat
                    </button>
                </td>
                <td class="p-4">
                    @php
                        $colorClass = match($ticket->status) {
                            'Masuk' => 'bg-red-100 text-red-800',
                            'Proses' => 'bg-yellow-100 text-yellow-800',
                            'Selesai' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                        {{ $ticket->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-6 text-gray-500">Belum ada tiket</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $tickets->withQueryString()->links() }}
</div>

<!-- Modal Detail Tiket -->
<div id="detailModal" class="modal-overlay hidden">
    <div class="modal-content">
        <h3>Detail Tiket</h3>
        <div class="modal-text bg-gray"><strong>Keluhan:</strong><br><span id="modalKeluhan"></span></div>
        <div class="modal-text bg-yellow"><strong>Eskalasi:</strong><br><span id="modalEskalasi"></span></div>
        <div class="modal-text bg-red"><strong>Prioritas:</strong><br><span id="modalPrioritas"></span></div>
        <div class="modal-text bg-gray"><strong>Keterangan Prioritas:</strong><br><span id="modalKeteranganPrioritas"></span></div>
        <div class="modal-footer">
            <button onclick="closeDetailModal()" class="btn-tutup">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showDetailModal(keluhan, eskalasi, prioritas, keteranganPrioritas) {
        document.getElementById('modalKeluhan').innerHTML = keluhan;
        document.getElementById('modalEskalasi').textContent = eskalasi;
        document.getElementById('modalPrioritas').textContent = prioritas;
        document.getElementById('modalKeteranganPrioritas').textContent = keteranganPrioritas;

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endpush
