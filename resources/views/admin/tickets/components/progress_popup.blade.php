<div class="p-4">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Histori Progres Tiket</h3>

    @forelse($progresses as $progress)
        <div class="mb-4 bg-gray-50 border border-gray-300 rounded-lg p-3 shadow-sm">
            <div class="text-sm text-gray-600 mb-1">
                <span class="block">
                    <strong>Oleh:</strong> {{ $progress->adminAplikasi->name ?? 'Tidak diketahui' }}
                </span>
                <span class="block">
                    <strong>Jam:</strong> {{ \Carbon\Carbon::parse($progress->waktu_progres)->format('H:i') }}
                    &nbsp; • &nbsp;
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($progress->created_at)->format('d M Y') }}
                </span>
            </div>
            <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line mt-2">
                {{ $progress->narasi }}
            </div>
        </div>
    @empty
        <div class="text-gray-500 text-sm italic">Belum ada progres ditambahkan untuk tiket ini.</div>
    @endforelse
</div>
