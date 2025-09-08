@extends('layouts.admin_aplikasi')

@section('title', 'Progres Tiket')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-4">Proses Tiket #{{ $ticket->ticket_number }}</h2>

    <!-- Info Tiket -->
    <div class="mb-6">
        <p><strong>Nama Pelapor:</strong> {{ $ticket->reporter_name }}</p>
        <p><strong>Keluhan:</strong> {{ $ticket->keluhan }}</p>
        <p><strong>Status Saat Ini:</strong> 
            <span class="inline-block px-2 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                {{ $ticket->status }}
            </span>
        </p>
    </div>

    <!-- Form Tambah Progres -->
    <form action="{{ route('aplikasi.tickets.progress.store', $ticket->id) }}" method="POST" class="mb-6 space-y-4">
        @csrf

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="narasi" class="block font-medium mb-1">Catatan Proses</label>
            <textarea name="narasi" id="narasi" rows="4" required
                class="w-full border border-gray-300 rounded-md p-2">{{ old('narasi') }}</textarea>
        </div>

        <!-- Jam Proses (otomatis, readonly) -->
        <div>
            <label for="waktu_progres" class="block font-medium mb-1">Jam Proses</label>
            <input type="time" name="waktu_progres" id="waktu_progres" 
                value="{{ now()->format('H:i') }}"
                class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 text-gray-700" readonly>
        </div>

        <!-- Diselesaikan oleh -->
        <div>
            <label class="block font-medium mb-1">Diselesaikan Oleh</label>
            <input type="text" value="{{ Auth::user()->name }}" 
                class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 text-gray-700" disabled>
        </div>

        <!-- Hidden input untuk status -->
        <input type="hidden" name="status" id="status_input" value="">

        <!-- Tombol Aksi -->
        <div class="flex justify-between items-center gap-4 pt-4">
            <a href="{{ route('aplikasi.tickets.masuk') }}" 
               class="bg-red-600 text-white px-5 py-2 rounded-full hover:bg-red-700 transition">
                Batal
            </a>
            <div class="flex gap-3">
                <!-- Tombol Simpan Progres -->
                <button type="submit" onclick="document.getElementById('status_input').value='proses'"
                    class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
                <!-- Tombol Selesaikan -->
                <button type="submit" onclick="document.getElementById('status_input').value='selesai'"
                    class="bg-green-600 text-white px-5 py-2 rounded-full hover:bg-green-700 transition">
                    Selesaikan Tiket
                </button>
            </div>
        </div>
    </form>

    <!-- Histori Progres -->
    <h3 class="text-lg font-semibold mb-2">Histori Progres</h3>
    @if($ticket->progresses->count())
        <ul class="space-y-3">
            @foreach($ticket->progresses as $progres)
                <li class="border border-gray-200 p-3 rounded-md bg-gray-50">
                    <div class="text-sm text-gray-600 mb-1">
                        Oleh: <strong>{{ $progres->adminAplikasi->name ?? 'Tidak diketahui' }}</strong>
                        • Jam: {{ $progres->waktu_progres ?? '-' }}
                        • Tanggal: {{ $progres->created_at->format('d M Y H:i') }}
                    </div>
                    <div class="text-gray-800">{{ $progres->narasi }}</div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-gray-500">Belum ada progres ditambahkan.</p>
    @endif
</div>

<!-- Script untuk set jam otomatis setiap reload -->
<script>
    document.getElementById('waktu_progres').value = new Date().toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
</script>
@endsection
