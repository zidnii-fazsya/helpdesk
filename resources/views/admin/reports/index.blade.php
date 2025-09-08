@extends('layouts.admin')

@section('content')
<div class="px-6 py-4">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Laporan</h1>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="relative bg-sky-400 text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform">
            <i class="bi bi-ticket-perforated-fill text-[5rem] absolute right-3 top-3 text-sky-300/30"></i>
            <div class="relative z-10">
                <p class="text-3xl font-bold">{{ number_format($totalTiket ?? 0) }}</p>
                <p class="text-sm opacity-90">Total Ticket Masuk</p>
                <div class="mt-3 text-sm font-semibold">{{ number_format($totalTiket ?? 0) }} Tiket</div>
            </div>
        </div>

        <div class="relative bg-green-600 text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform">
            <i class="bi bi-hourglass-split text-[5rem] absolute right-3 top-3 text-green-300/30"></i>
            <div class="relative z-10">
                <p class="text-3xl font-bold">{{ number_format($tiketProses ?? 0) }}</p>
                <p class="text-sm opacity-90">Ticket Sedang Proses</p>
                <div class="mt-3 text-sm font-semibold">{{ number_format($tiketProses ?? 0) }} Tiket</div>
            </div>
        </div>

        <div class="relative bg-yellow-500 text-white p-6 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform">
            <i class="bi bi-clipboard2-check text-[5rem] absolute right-3 top-3 text-yellow-300/30"></i>
            <div class="relative z-10">
                <p class="text-3xl font-bold">{{ number_format($tiketSelesai ?? 0) }}</p>
                <p class="text-sm opacity-90">Ticket Selesai</p>
                <div class="mt-3 text-sm font-semibold">{{ number_format($tiketSelesai ?? 0) }} Tiket</div>
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-lg p-4 mb-8 flex items-center shadow" style="border-color: #3b82f6;">
        <div class="p-3 rounded-lg mr-4 flex-shrink-0" style="background-color: #3b82f6;">
            <i class="bi bi-file-earmark-text text-white text-xl"></i>
        </div>
        <div class="font-medium text-lg text-[#2563eb]">
            Simpan Laporan bulanan atau tahunan ada !!!
        </div>
    </div>

    <!-- Laporan Bulanan -->
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Laporan Bulanan</h2>
            <form action="{{ route('admin.reports.export.bulanan') }}" method="GET">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 min-w-0">
                        <select name="month" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white">
                            <option disabled selected>Pilih Bulan</option>
                            @foreach ([
                                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                            ] as $bulan)
                                <option value="{{ $bulan }}">{{ $bulan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-0">
                        <select name="year" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white">
                            <option disabled selected>Pilih Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit"
                            class="text-white font-semibold px-8 py-3 rounded-lg transition"
                            style="background-color: #3b82f6;"
                            onmouseover="this.style.backgroundColor='#2563eb'"
                            onmouseout="this.style.backgroundColor='#3b82f6'">
                            <i class="bi bi-download mr-2"></i>Simpan PDF laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Laporan Tahunan -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mt-8">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Laporan Tahunan</h2>
            <form action="{{ route('admin.reports.export.tahunan') }}" method="GET">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 min-w-0">
                        <select name="year" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white">
                            <option disabled selected>Pilih Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit"
                            class="text-white font-semibold px-8 py-3 rounded-lg transition"
                            style="background-color: #3b82f6;"
                            onmouseover="this.style.backgroundColor='#2563eb'"
                            onmouseout="this.style.backgroundColor='#3b82f6'">
                            <i class="bi bi-download mr-2"></i>Simpan PDF laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
