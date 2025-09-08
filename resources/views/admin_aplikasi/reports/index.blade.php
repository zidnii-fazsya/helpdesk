@extends('layouts.admin_aplikasi')

@section('title', 'Laporan Tiket')

@section('content')
<div class="px-6 py-4">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Laporan - {{ ucfirst($aplikasiName) }}</h1>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Total Tiket -->
        <div class="relative bg-sky-400 text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform">
            <i class="bi bi-ticket-perforated-fill text-[5rem] absolute right-3 top-3 text-sky-300/30"></i>
            <div class="relative z-10">
                <p class="text-3xl font-bold">{{ number_format($totalTiket ?? 0) }}</p>
                <p class="text-sm opacity-90">Total Tiket Masuk</p>
                <div class="mt-3 text-sm font-semibold">{{ number_format($totalTiket ?? 0) }} Tiket</div>
            </div>
        </div>

        <!-- Tiket Proses -->
        <div class="relative bg-green-600 text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform">
            <i class="bi bi-hourglass-split text-[5rem] absolute right-3 top-3 text-green-300/30"></i>
            <div class="relative z-10">
                <p class="text-3xl font-bold">{{ number_format($tiketProses ?? 0) }}</p>
                <p class="text-sm opacity-90">Tiket Sedang Proses</p>
                <div class="mt-3 text-sm font-semibold">{{ number_format($tiketProses ?? 0) }} Tiket</div>
            </div>
        </div>

        <!-- Tiket Selesai -->
        <div class="relative bg-yellow-500 text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform">
            <i class="bi bi-clipboard2-check text-[5rem] absolute right-3 top-3 text-yellow-300/30"></i>
            <div class="relative z-10">
                <p class="text-3xl font-bold">{{ number_format($tiketSelesai ?? 0) }}</p>
                <p class="text-sm opacity-90">Tiket Selesai</p>
                <div class="mt-3 text-sm font-semibold">{{ number_format($tiketSelesai ?? 0) }} Tiket</div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-white border rounded-lg p-4 mb-8 flex items-center shadow" style="border-color: #63b3ed;">
        <div class="p-3 rounded-lg mr-4 flex-shrink-0" style="background-color: #63b3ed;">
            <i class="bi bi-file-earmark-text text-white text-xl"></i>
        </div>
        <div class="font-medium text-lg text-[#4299e1]">
            Simpan Laporan Bulanan atau Tahunan untuk Aplikasi {{ ucfirst($aplikasiName) }}
        </div>
    </div>

    <!-- Laporan Bulanan -->
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Laporan Bulanan</h2>
            <form action="{{ route('aplikasi.reports.bulanan') }}" method="GET">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 min-w-0">
                        <select name="bulan" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white" required>
                            <option disabled selected>Pilih Bulan</option>
                            @foreach ([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ] as $key => $bulan)
                                <option value="{{ $key }}">{{ $bulan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-0">
                        <input type="number" name="tahun" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white"
                            placeholder="Tahun" min="2020" value="{{ date('Y') }}" required>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-3 rounded-lg transition">
                            <i class="bi bi-download mr-2"></i>Download PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Laporan Tahunan -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mt-8">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Laporan Tahunan</h2>
            <form action="{{ route('aplikasi.reports.tahunan') }}" method="GET">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 min-w-0">
                        <input type="number" name="tahun" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white"
                            placeholder="Tahun" min="2020" value="{{ date('Y') }}" required>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-3 rounded-lg transition">
                            <i class="bi bi-download mr-2"></i>Download PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection