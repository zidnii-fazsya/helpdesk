@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-4">Tiket Masuk</h1>

{{-- Form Pencarian --}}
<form method="GET" action="{{ route('admin.tickets.masuk') }}" 
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
        <a href="{{ route('admin.tickets.masuk') }}" class="text-sm text-red-500 underline">Reset</a>
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
                <th class="px-4 py-3">Ruangan</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Nama Aplikasi / Subkategori</th>
                <th class="px-4 py-3">Prioritas</th>
                <th class="px-4 py-3">Keluhan</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
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
                    <td class="px-4 py-3">{{ $ticket->ruangan }}</td>
                    <td class="px-4 py-3 text-center">{{ $ticket->kategori ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($ticket->kategori === 'Perangkat Lunak')
                            {{ $ticket->aplikasi->nama_aplikasi ?? '-' }}
                        @else
                            @if($ticket->subkategoris && $ticket->subkategoris->count() > 0)
                                {{ $ticket->subkategoris->pluck('nama_subkategori')->join(', ') }}
                            @else
                                -
                            @endif
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
                        <button class="text-white text-xs font-semibold px-3 py-1 rounded {{ $prioritasColor }} hover:opacity-90 transition"
                                onclick='showPrioritasDetail(@json($ticket->keterangan_prioritas_formatted))'>
                            {{ ucfirst($ticket->prioritas) }}
                        </button>
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        {{ \Illuminate\Support\Str::words($ticket->keluhan, 6, '...') }}
                        <button class="text-blue-600 text-xs underline ml-1 hover:text-blue-800"
                                onclick='showKeluhanDetail(@json($ticket->keluhan), @json($ticket->keterangan_prioritas_formatted))'>
                            Detail
                        </button>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $colorClass = match($ticket->status) {
                                'Masuk' => 'bg-red-100 text-red-800',
                                'Proses' => 'bg-yellow-100 text-yellow-800',
                                'Selesai' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-block {{ $colorClass }} text-xs font-semibold px-3 py-1 rounded">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" 
                           class="text-blue-500 hover:text-blue-700 transition"
                           title="Edit Tiket">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="px-4 py-8 text-center text-gray-500">
                        Belum ada tiket masuk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Detail Keluhan --}}
<div id="keluhanModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold" id="modalTitle">Detail Tiket</h2>
            <button onclick="closeKeluhanModal()" class="text-gray-600 hover:text-gray-800 text-2xl font-bold">&times;</button>
        </div>
        <div class="p-4 text-sm text-gray-800 whitespace-pre-line" id="modalBody">
            {{-- Isi dinamis via JS --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showKeluhanDetail(keluhan, keteranganPrioritas) {
    document.getElementById('modalTitle').textContent = 'Detail Tiket';
    document.getElementById('modalBody').innerHTML = `
        <p class="font-semibold mb-1">Keluhan:</p>
        <div class="mb-3">${keluhan || '-'}</div>
        <p class="font-semibold mb-1">Keterangan Prioritas:</p>
        <div>${keteranganPrioritas || '-'}</div>
    `;
    document.getElementById('keluhanModal').classList.remove('hidden');
}

function showPrioritasDetail(keteranganPrioritas) {
    document.getElementById('modalTitle').textContent = 'Keterangan Prioritas';
    document.getElementById('modalBody').innerHTML = `
        <div>${keteranganPrioritas || '-'}</div>
    `;
    document.getElementById('keluhanModal').classList.remove('hidden');
}

function closeKeluhanModal() {
    document.getElementById('keluhanModal').classList.add('hidden');
}
</script>
@endpush
