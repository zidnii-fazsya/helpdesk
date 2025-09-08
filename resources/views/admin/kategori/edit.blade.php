@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4">Edit Kategori</h2>

    {{-- Notifikasi jika ada error --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit --}}
    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori"
                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
            <button type="submit"
                class="w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md"
                style="background-color: #4299e1;">
                <i class="bi bi-check-circle mr-2"></i>Update Kategori
            </button>
            <a href="{{ route('admin.kategori.index') }}"
                class="w-full sm:w-auto bg-white text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-8 rounded-full text-center transition duration-300">
                <i class="bi bi-x-circle mr-2"></i>Batal
            </a>
        </div>
    </form>
@endsection
