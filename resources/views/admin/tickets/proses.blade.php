@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-4">Tiket Proses</h1>

{{-- Form Pencarian --}}
<form method="GET" action="{{ route('admin.tickets.proses') }}" 
      class="mb-6 flex flex-wrap justify-end gap-4 items-center">
    <input
        type="text"
        name="nama"
        value="{{ request('nama') }}"
        placeholder="Cari Nama Pelapor"
        class="border border-gray-300 rounded px-4 py-2 text-sm w-52"
    />

    <input
        type="date"
        name="tanggal"
        value="{{ request('tanggal') }}"
        class="border border-gray-300 rounded px-4 py-2 text-sm"
    />

    <button
        type="submit"
        class="px-4 py-2 rounded text-white text-sm transition"
        style="background-color: #3b82f6;"
        onmouseover="this.style.backgroundColor='#2563eb'"
        onmouseout="this.style.backgroundColor='#3b82f6'"
    >
        Cari
    </button>

    @if(request('nama') || request('tanggal'))
        <a href="{{ route('admin.tickets.proses') }}" class="text-sm text-red-500 underline">Reset</a>
    @endif
</form>

{{-- Tabel Tiket --}}
<div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full w-full table-auto border border-gray-200 text-sm text-left">
        <thead class="text-white" style="background-color: #3b82f6;">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">No. Tiket</th>
                <th class="px-4 py-3">Nama Pelapor</th>
                <th class="px-4 py-3">Jabatan</th>
                <th class="px-4 py-3">Satuan Kerja</th>
                <th class="px-4 py-3">Keluhan</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Nama Aplikasi / Subkategori</th>
                <th class="px-4 py-3">Prioritas</th>
                <th class="px-4 py-3">Teknisi</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @forelse ($tickets as $ticket)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-center">{{ \Carbon\Carbon::parse($ticket->tanggal)->format('d-m-Y') }}</td>
                    <td class="px-4 py-3 font-mono text-center">{{ $ticket->ticket_number }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $ticket->reporter_name }}</td>
                    <td class="px-4 py-3">{{ $ticket->jabatan }}</td>
                    <td class="px-4 py-3">{{ $ticket->satuan_kerja }}</td>
                    
                    {{-- Keluhan --}}
                    <td class="px-4 py-3 max-w-xs">
                        {{ \Illuminate\Support\Str::words($ticket->keluhan, 4, '...') }}
                        <a href="javascript:void(0);" 
                           class="text-blue-600 text-xs underline ml-1"
                           onclick="showKeluhanDetail(`{{ addslashes($ticket->keluhan) }}`)">
                            Detail
                        </a>
                    </td>

                    {{-- Kategori --}}
                    <td class="px-4 py-3 text-center">{{ $ticket->kategori ?? '-' }}</td>

                    {{-- Nama Aplikasi / Subkategori --}}
                    <td class="px-4 py-3 text-center">
                        @if($ticket->kategori === 'Perangkat Lunak')
                            {{ $ticket->aplikasi->nama_aplikasi ?? '-' }}
                        @else
                            {{ $ticket->subkategoris->first()->nama_subkategori ?? '-' }}
                        @endif
                    </td>

                    {{-- Prioritas --}}
                    <td class="px-4 py-3 text-center">
                        @php
                            $prioritasClass = match(strtolower($ticket->prioritas)) {
                                'tinggi' => 'bg-red-500',
                                'sedang' => 'bg-yellow-500',
                                'rendah' => 'bg-green-500',
                                default => 'bg-gray-400',
                            };
                        @endphp
                        <span class="inline-block text-white text-xs font-semibold px-2 py-1 rounded {{ $prioritasClass }}"
                              style="cursor:pointer"
                              onclick='showPrioritasDetail(@json($ticket->keterangan_prioritas_formatted))'>
                            {{ ucfirst($ticket->prioritas ?? '-') }}
                        </span>
                    </td>

                    {{-- Teknisi --}}
                    <td class="px-4 py-3 text-center">
                        {{ $ticket->teknisi_nama ?? '-' }}
                        @if($ticket->teknisi_nip)
                            <div class="text-xs text-gray-500">{{ $ticket->teknisi_nip }}</div>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3 text-center">
                        <span onclick="showProgressPopup({{ $ticket->id }})"
                              class="cursor-pointer inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded hover:bg-yellow-200">
                            {{ $ticket->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada tiket berstatus proses.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Detail Progres --}}
<div id="progressModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Detail Progres Tiket</h2>
            <button onclick="closeProgressModal()" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <div id="modalContent" class="p-4">Memuat...</div>
    </div>
</div>

{{-- Modal Detail Keluhan --}}
<div id="keluhanModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Detail Keluhan</h2>
            <button onclick="closeKeluhanModal()" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <div id="keluhanContent" class="p-4 text-sm text-gray-800 whitespace-pre-line">
            <!-- Keluhan tampil di sini -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showProgressPopup(ticketId) {
    const modal = document.getElementById('progressModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    content.innerHTML = 'Memuat...';

    fetch(`/admin/tickets/${ticketId}/progress`)
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data progres');
            return response.text();
        })
        .then(html => content.innerHTML = html)
        .catch(() => content.innerHTML = '<p class="text-red-600 text-sm">Gagal memuat data progres.</p>');
}

function closeProgressModal() {
    document.getElementById('progressModal').classList.add('hidden');
}

function showKeluhanDetail(keluhan) {
    const modal = document.getElementById('keluhanModal');
    const content = document.getElementById('keluhanContent');
    modal.classList.remove('hidden');
    content.textContent = keluhan;
}

function closeKeluhanModal() {
    document.getElementById('keluhanModal').classList.add('hidden');
}

function showPrioritasDetail(keteranganPrioritas) {
    alert(keteranganPrioritas || '-'); // Bisa diganti dengan modal detail jika mau
}
</script>
@endpush
