<!-- Modal Detail Keluhan -->
<div id="modal-detail-{{ $ticket->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg max-w-md w-full shadow-xl relative">
        <h2 class="text-xl font-bold mb-4">Detail Keluhan</h2>

        <div class="space-y-2 text-sm">
            <p><strong>No Tiket:</strong> {{ $ticket->ticket_number }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($ticket->tanggal)->format('d-m-Y') }}</p>
            <p><strong>Nama Pelapor:</strong> {{ $ticket->reporter_name }}</p>
            <p><strong>Jabatan:</strong> {{ $ticket->jabatan }}</p>
            <p><strong>Ruangan:</strong> {{ $ticket->ruangan }}</p>
            <p><strong>Kategori:</strong> {{ $ticket->kategori }}</p>
            <p><strong>Keluhan:</strong><br>{{ $ticket->keluhan }}</p>
        </div>

        <div class="mt-6 text-right">
            <button onclick="closeModal({{ $ticket->id }})"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Ambil Tiket -->
<div id="modal-ambil-{{ $ticket->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg max-w-md w-full shadow-xl relative">
        <h2 class="text-xl font-bold mb-4">Ambil Tiket</h2>

        <div class="space-y-2 text-sm">
            <p><strong>No Tiket:</strong> {{ $ticket->ticket_number }}</p>
            <p><strong>Keluhan:</strong><br>{{ $ticket->keluhan }}</p>
        </div>

        <form action="{{ route('aplikasi.tickets.assign', $ticket->id) }}" method="POST" class="mt-4 flex justify-end gap-2">
            @csrf
            <input type="hidden" name="teknisi_nama" value="{{ auth()->user()->name }}">
            <input type="hidden" name="teknisi_nip" value="{{ auth()->user()->nip }}">
            <input type="hidden" name="status" value="Proses">

            <button type="button" onclick="closeModalAmbil({{ $ticket->id }})"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                Tidak
            </button>
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Iya, Ambil
            </button>
        </form>
    </div>
</div>

<!-- JS untuk modal -->
<script>
    function openModal(id) {
        const modal = document.getElementById('modal-detail-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById('modal-detail-' + id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openModalAmbil(id) {
        const modal = document.getElementById('modal-ambil-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModalAmbil(id) {
        const modal = document.getElementById('modal-ambil-' + id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
