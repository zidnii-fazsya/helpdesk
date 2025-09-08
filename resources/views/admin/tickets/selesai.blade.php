@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Tiket dengan Status <span class="text-green-600">Selesai</span></h2>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('admin.tickets.selesai') }}" class="mb-6 flex flex-wrap justify-end gap-4 items-center">
        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari Nama Pelapor"
               class="border border-gray-300 rounded px-4 py-2 text-sm w-56" />

        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
               class="border border-gray-300 rounded px-4 py-2 text-sm" />

        <button type="submit"
            class="px-4 py-2 rounded text-white text-sm transition"
            style="background-color: #3b82f6;"
            onmouseover="this.style.backgroundColor='#2563eb'"
            onmouseout="this.style.backgroundColor='#3b82f6'">
            Cari
        </button>

        @if(request('nama') || request('tanggal'))
            <a href="{{ route('admin.tickets.selesai') }}" class="text-sm text-red-500 underline">Reset</a>
        @endif
    </form>

    {{-- Tabel Data --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full table-auto text-sm border border-gray-200">
            <thead style="background-color: #3b82f6;" class="text-white font-bold">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">No. Tiket</th>
                    <th class="px-4 py-3">Nama Pelapor</th>
                    <th class="px-4 py-3">Jabatan</th>
                    <th class="px-4 py-3">Satuan Kerja</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Nama Aplikasi / Subkategori</th>
                    <th class="px-4 py-3">Detail Tiket</th>
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

                        {{-- Tombol Lihat (sesudah Nama Aplikasi / Subkategori) --}}
                        <td class="px-4 py-3 text-center">
                            <button onclick="showDetail(
                                `{{ addslashes($ticket->eskalasi ?? '-') }}`,
                                `{{ addslashes($ticket->keluhan ?? '-') }}`,
                                `{{ addslashes($ticket->keterangan_prioritas ?? '-') }}`,
                                `{{ ucfirst($ticket->prioritas ?? '-') }}`
                            )"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm shadow">
                                Lihat
                            </button>
                        </td>

                        {{-- Prioritas --}}
                        <td class="px-4 py-3 text-center">
                            @php
                                $prioritasColor = match(strtolower($ticket->prioritas)) {
                                    'tinggi' => 'bg-red-500',
                                    'sedang' => 'bg-yellow-500',
                                    'rendah' => 'bg-green-500',
                                    default => 'bg-gray-400',
                                };
                            @endphp
                            <span class="inline-block text-white text-xs font-semibold px-2 py-1 rounded {{ $prioritasColor }}">
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
                            <span class="inline-block bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded">
                                {{ $ticket->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center py-8 text-gray-500">
                            Tidak ada tiket berstatus selesai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-[400px] max-h-[80vh] overflow-auto">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Detail Tiket</h2>
            <button onclick="closeDetailModal()" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <div class="p-4 text-sm text-gray-800 space-y-3">
            <div>
                <strong>Eskalasi:</strong>
                <p id="detailEskalasi" class="mt-1 text-gray-700"></p>
            </div>
            <div>
                <strong>Narasi Keluhan:</strong>
                <p id="detailKeluhan" class="mt-1 text-gray-700 whitespace-pre-line"></p>
            </div>
            <div>
                <strong>Keterangan Prioritas:</strong>
                <p id="detailKeteranganPrioritas" class="mt-1 text-gray-700"></p>
            </div>
            <div>
                <strong>Status Prioritas:</strong>
                <p id="detailStatusPrioritas" class="mt-1 font-semibold"></p>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetail(eskalasi, keluhan, keteranganPrioritas, statusPrioritas) {
        document.getElementById('detailEskalasi').textContent = eskalasi;
        document.getElementById('detailKeluhan').textContent = keluhan;
        document.getElementById('detailKeteranganPrioritas').textContent = keteranganPrioritas;
        document.getElementById('detailStatusPrioritas').textContent = statusPrioritas;

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endsection
