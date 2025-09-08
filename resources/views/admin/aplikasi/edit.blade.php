@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4">Edit Aplikasi</h2>

    <form action="{{ route('admin.aplikasi.update', $aplikasi->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nama_aplikasi" class="block text-sm font-medium text-gray-700">Nama Aplikasi</label>
            <input type="text" name="nama_aplikasi" id="nama_aplikasi" value="{{ $aplikasi->nama_aplikasi }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

{{-- Tombol Submit --}}
            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                <button type="submit"
                    class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md"
                    style="background-color: #4299e1;">
                    <i class="bi bi-check-circle mr-2"></i>Simpan Aplikasi
                </button>
                <a href="{{ route('admin.aplikasi.index') }}"
                    class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-8 rounded-full text-center transition duration-300">
                    <i class="bi bi-x-circle mr-2"></i>Batal
                </a>
            </div>
            
    </form>
@endsection
