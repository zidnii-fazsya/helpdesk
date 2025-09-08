@extends('layouts.admin')

@section('title', 'Tambah Tiket - Helpdesk')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="bi bi-plus-circle text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Tiket</h2>
                <p class="text-gray-600">Silakan isi form berikut untuk membuat tiket baru</p>
            </div>
        </div>
    </div>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Pesan error --}}
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
        {{-- Form store tiket --}}
        <form action="{{ route('admin.tickets.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Identitas Pelapor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-calendar-date mr-2 text-blue-600"></i>Tanggal
                    </label>
                    <input type="date" id="tanggal" name="tanggal"
                        value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror"
                        required>
                </div>

                <div>
                    <label for="reporter_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-person mr-2 text-blue-600"></i>Nama Pelapor
                    </label>
                    <input type="text" id="reporter_name" name="reporter_name"
                        value="{{ old('reporter_name') }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('reporter_name') border-red-500 @enderror"
                        required>
                </div>

                <div>
                    <label for="jabatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-briefcase mr-2 text-blue-600"></i>Jabatan
                    </label>
                    <input type="text" id="jabatan" name="jabatan"
                        value="{{ old('jabatan') }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('jabatan') border-red-500 @enderror"
                        required>
                </div>

                <div>
                    <label for="satuan_kerja" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-building-add mr-2 text-blue-600"></i>Satuan Kerja
                    </label>
                    <input type="text" id="satuan_kerja" name="satuan_kerja"
                        value="{{ old('satuan_kerja') }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('satuan_kerja') border-red-500 @enderror"
                        required>
                </div>
            </div>

            <!-- Kategori + Subkategori/Aplikasi + Ruangan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-tags mr-2 text-blue-600"></i>Kategori
                    </label>
                    <select name="kategori_id" id="kategori_id" required
                        class="w-full border rounded-lg px-4 py-3 @error('kategori_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="subkategori_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-diagram-3 mr-2 text-blue-600"></i>Subkategori / Aplikasi
                    </label>
                    <select name="subkategori_id" id="subkategori_id" required
                        class="w-full border rounded-lg px-4 py-3 @error('subkategori_id') border-red-500 @enderror">
                        <option value="">-- Pilih Subkategori / Aplikasi --</option>
                    </select>
                </div>

                <div>
                    <label for="ruangan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-building mr-2 text-blue-600"></i>Ruangan
                    </label>
                    <input type="text" id="ruangan" name="ruangan"
                        value="{{ old('ruangan') }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('ruangan') border-red-500 @enderror"
                        required>
                </div>
            </div>

            <!-- Eskalasi & Prioritas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="eskalasi" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-arrow-up-right-circle mr-2 text-blue-600"></i>Eskalasi
                    </label>
                    <input type="text" id="eskalasi" name="eskalasi"
                        value="{{ old('eskalasi') }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('eskalasi') border-red-500 @enderror">
                </div>

                <div>
                    <label for="prioritas" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-exclamation-circle mr-2 text-blue-600"></i>Skala Prioritas
                    </label>
                    <select name="prioritas" id="prioritas"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('prioritas') border-red-500 @enderror">
                        <option value="">-- Pilih Prioritas --</option>
                        <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="keterangan_prioritas" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-card-text mr-2 text-blue-600"></i>Keterangan Prioritas
                </label>
                <textarea id="keterangan_prioritas" name="keterangan_prioritas" rows="3"
                    class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('keterangan_prioritas') border-red-500 @enderror">{{ old('keterangan_prioritas') }}</textarea>
            </div>

            <!-- SLA & Waktu Respon -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sla" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-clock-history mr-2 text-blue-600"></i>SLA (menit)
                    </label>
                    <input type="number" id="sla" name="sla"
                        value="{{ old('sla') }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('sla') border-red-500 @enderror"
                        min="1">
                </div>

                <div>
                    <label for="waktu_respon" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-stopwatch mr-2 text-blue-600"></i>Waktu Respon (menit)
                    </label>
                    <input type="number" id="waktu_respon" name="waktu_respon"
                        value="{{ old('waktu_respon') }}"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('waktu_respon') border-red-500 @enderror"
                        min="1">
                </div>
            </div>

            <div>
                <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="bi bi-chat-square-text mr-2 text-blue-600"></i>Keluhan
                </label>
                <textarea id="keluhan" name="keluhan" rows="4"
                    class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 @error('keluhan') border-red-500 @enderror"
                    required>{{ old('keluhan') }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                <button type="submit"
                    class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md"
                    style="background-color: #63b3ed;">
                    <i class="bi bi-check-circle mr-2"></i>Simpan Tiket
                </button>
                <a href="{{ route('admin.dashboard') }}"
                    class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-8 rounded-full text-center transition duration-300">
                    <i class="bi bi-x-circle mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript: Filter Subkategori tanpa AJAX --}}
<script>
    const kategoris = @json($kategoris); // Ambil data kategori + subkategori dari controller
    const kategoriSelect = document.getElementById('kategori_id');
    const subkategoriSelect = document.getElementById('subkategori_id');

    kategoriSelect.addEventListener('change', function () {
        const selectedId = this.value;
        subkategoriSelect.innerHTML = '<option value="">-- Pilih Subkategori / Aplikasi --</option>';

        if (selectedId) {
            const kategori = kategoris.find(k => k.id == selectedId);
            if (kategori && kategori.subkategoris.length > 0) {
                kategori.subkategoris.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.nama_subkategori;
                    subkategoriSelect.appendChild(option);
                });
            }
        }
    });
</script>

<style>
    button[type="submit"]:hover {
        background-color: #4299e1 !important;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
            0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection
