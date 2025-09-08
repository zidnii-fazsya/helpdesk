@extends('layouts.admin')

@section('title', 'Edit Tiket - Helpdesk')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="bi bi-pencil-square text-yellow-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Tiket</h2>
                <p class="text-gray-600">Ubah data tiket sesuai kebutuhan, selama tiket belum diproses</p>
            </div>
        </div>
    </div>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-green-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Error --}}
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center gap-2 mb-2">
            <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
            <span class="font-semibold">Terjadi kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tanggal --}}
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-calendar-date mr-2 text-blue-600"></i>Tanggal
                    </label>
                    <input type="date" id="tanggal" name="tanggal" 
                        value="{{ old('tanggal', $ticket->tanggal->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Nama Pelapor --}}
                <div>
                    <label for="reporter_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-person mr-2 text-blue-600"></i>Nama Pelapor
                    </label>
                    <input type="text" id="reporter_name" name="reporter_name" 
                        value="{{ old('reporter_name', $ticket->reporter_name) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Jabatan --}}
                <div>
                    <label for="jabatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-briefcase mr-2 text-blue-600"></i>Jabatan
                    </label>
                    <input type="text" id="jabatan" name="jabatan" 
                        value="{{ old('jabatan', $ticket->jabatan) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Ruangan --}}
                <div>
                    <label for="ruangan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-building mr-2 text-blue-600"></i>Ruangan
                    </label>
                    <input type="text" id="ruangan" name="ruangan" 
                        value="{{ old('ruangan', $ticket->ruangan) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-tags mr-2 text-blue-600"></i>Kategori
                    </label>
                    <select name="kategori_id" id="kategori_id" required
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('kategori_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Satuan Kerja --}}
                <div>
                    <label for="satuan_kerja" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-building-add mr-2 text-blue-600"></i>Satuan Kerja
                    </label>
                    <input type="text" id="satuan_kerja" name="satuan_kerja" 
                        value="{{ old('satuan_kerja', $ticket->satuan_kerja) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('satuan_kerja') border-red-500 @enderror"
                        required>
                </div>

                {{-- Subkategori / Aplikasi --}}
                <div>
                    <label for="subkategori_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-diagram-3 mr-2 text-blue-600"></i>Subkategori / Aplikasi
                    </label>
                    <select name="subkategori_id" id="subkategori_id"
                        class="w-full border rounded-lg px-4 py-3 @error('subkategori_id') border-red-500 @enderror">
                        <option value="">-- Pilih Subkategori / Aplikasi --</option>
                    </select>
                </div>

                {{-- Skala Prioritas --}}
                <div>
                    <label for="prioritas" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-exclamation-circle mr-2 text-blue-600"></i>Skala Prioritas
                    </label>
                    <select name="prioritas" id="prioritas"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        <option value="tinggi" {{ old('prioritas', $ticket->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="sedang" {{ old('prioritas', $ticket->prioritas) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="rendah" {{ old('prioritas', $ticket->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>

                {{-- Waktu Respon --}}
                <div>
                    <label for="waktu_respon" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-stopwatch mr-2 text-blue-600"></i>Waktu Respon (jam)
                    </label>
                    <input type="number" id="waktu_respon" name="waktu_respon" 
                        value="{{ old('waktu_respon', $ticket->waktu_respon) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- SLA --}}
                <div>
                    <label for="sla" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-clock-history mr-2 text-blue-600"></i>SLA (jam)
                    </label>
                    <input type="number" id="sla" name="sla" 
                        value="{{ old('sla', $ticket->sla) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Eskalasi --}}
                <div>
                    <label for="eskalasi" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-arrow-up-right-circle mr-2 text-blue-600"></i>Eskalasi
                    </label>
                    <input type="text" id="eskalasi" name="eskalasi" 
                        value="{{ old('eskalasi', $ticket->eskalasi) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Keterangan Prioritas --}}
                <div class="md:col-span-2">
                    <label for="keterangan_prioritas" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-card-text mr-2 text-blue-600"></i>Keterangan Prioritas
                    </label>
                    <textarea id="keterangan_prioritas" name="keterangan_prioritas" rows="3"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('keterangan_prioritas', $ticket->keterangan_prioritas) }}</textarea>
                </div>
            </div>

            {{-- Keluhan --}}
            <div>
                <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-chat-square-text mr-2 text-blue-600"></i>Keluhan
                </label>
                <textarea id="keluhan" name="keluhan" rows="4"
                    class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('keluhan', $ticket->keluhan) }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                <button type="submit"
                    class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md"
                    style="background-color: #f6ad55;">
                    <i class="bi bi-check-circle mr-2"></i>Update Tiket
                </button>
                <a href="{{ route('admin.tickets.index') }}"
                    class="w-full sm:w-auto bg-white text-gray-600 border border-gray-400 hover:bg-gray-100 font-bold py-3 px-8 rounded-full text-center transition duration-300">
                    <i class="bi bi-x-circle mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kategori_id');
        const subkategoriSelect = document.getElementById('subkategori_id');
        const getOptionsUrl = "{{ route('admin.get-options') }}";
        const selectedSubkategori = "{{ old('subkategori_id', $ticket->subkategori_id) }}";
        const selectedAplikasi = "{{ old('aplikasi_id', $ticket->aplikasi_id) }}";

        function updateSubkategoriOptions() {
            const selectedId = kategoriSelect.value;
            subkategoriSelect.innerHTML = '<option value="">-- Memuat... --</option>';

            if (!selectedId) {
                subkategoriSelect.innerHTML = '<option value="">-- Pilih Subkategori / Aplikasi --</option>';
                return;
            }

            fetch(`${getOptionsUrl}?kategori_id=${selectedId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(options => {
                    subkategoriSelect.innerHTML = '<option value="">-- Pilih Subkategori / Aplikasi --</option>';
                    if (options.length > 0) {
                        options.forEach(option => {
                            const optionElement = document.createElement('option');
                            optionElement.value = option.id;
                            optionElement.textContent = option.nama_subkategori || option.nama_aplikasi;
                            if (option.id == selectedSubkategori || option.id == selectedAplikasi) {
                                optionElement.selected = true;
                            }
                            subkategoriSelect.appendChild(optionElement);
                        });
                    } else {
                        subkategoriSelect.innerHTML = '<option value="">-- Tidak ada pilihan --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching options:', error);
                    subkategoriSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
                });
        }

        kategoriSelect.addEventListener('change', updateSubkategoriOptions);

        // Initial population
        if (kategoriSelect.value) {
            updateSubkategoriOptions();
        }
    });
</script>
@endsection
