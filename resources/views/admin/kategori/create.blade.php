@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-lg mx-auto">
    <h2 class="text-xl font-semibold mb-4">Tambah Kategori</h2>

    <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="nama_kategori" class="block font-medium">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" required
                   class="w-full border rounded px-4 py-2 mt-1">
        </div>

                    {{-- Tombol Submit --}}
            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                <button type="submit"
                    class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md"
                    style="background-color: #4299e1;">
                    <i class="bi bi-check-circle mr-2"></i>Simpan Kategori
                </button>
                <a href="{{ route('admin.aplikasi.index') }}"
                    class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-8 rounded-full text-center transition duration-300">
                    <i class="bi bi-x-circle mr-2"></i>Batal
                </a>
            </div>
    </form>
</div>
@endsection
