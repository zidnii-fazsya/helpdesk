@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $aplikasiName = $aplikasiName ?? 'Aplikasi';
@endphp

<style>
    .sidebar-app-bg {
        background-color: #1e3a8a; /* Biru gelap yang sama dengan admin helpdesk */
    }
    .sidebar-app-hover:hover {
        background-color: #1d4ed8; /* bg-blue-700 untuk hover */
    }
    .sidebar-app-active {
        background-color: #1d4ed8; /* bg-blue-700 untuk active */
        background-opacity: 0.8;
    }
</style>

<aside class="w-72 min-h-screen sidebar-app-bg p-6 flex flex-col justify-between">
    <div>
        <!-- Logo & Nama Aplikasi -->
        <div class="flex items-center gap-3 mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo KEMLU" class="w-9 h-9 object-contain">
            <span class="text-base font-bold text-white">Admin {{ ucwords($aplikasiName) }}</span>
        </div>

        <!-- Menu Navigation -->
        <ul class="space-y-3 text-white font-medium text-sm">
            <!-- Dashboard -->
            <li class="rounded-xl px-4 py-2 sidebar-app-hover {{ $currentRoute === 'aplikasi.dashboard' ? 'sidebar-app-active' : '' }}">
                <a href="{{ route('aplikasi.dashboard') }}" class="flex items-center">
                    <i class="bi bi-columns-gap mr-3 text-sm"></i> Dashboard
                </a>
            </li>

            <!-- Semua Tiket -->
            <li class="rounded-xl px-4 py-2 sidebar-app-hover {{ $currentRoute === 'aplikasi.tickets' ? 'sidebar-app-active' : '' }}">
                <a href="{{ route('aplikasi.tickets') }}" class="flex items-center">
                    <i class="bi bi-list-task mr-3 text-sm"></i> Semua Tiket
                </a>
            </li>

            <!-- Tiket Masuk -->
            <li class="rounded-xl px-4 py-2 sidebar-app-hover {{ $currentRoute === 'aplikasi.tickets.masuk' ? 'sidebar-app-active' : '' }}">
                <a href="{{ route('aplikasi.tickets.masuk') }}" class="flex items-center">
                    <i class="bi bi-inbox-fill mr-3 text-sm"></i> Tiket Masuk
                </a>
            </li>

            <!-- Tiket Proses -->
            <li class="rounded-xl px-4 py-2 sidebar-app-hover {{ $currentRoute === 'aplikasi.tickets.proses' ? 'sidebar-app-active' : '' }}">
                <a href="{{ route('aplikasi.tickets.proses') }}" class="flex items-center">
                    <i class="bi bi-arrow-repeat mr-3 text-sm"></i> Tiket Proses
                </a>
            </li>

            <!-- Tiket Selesai -->
            <li class="rounded-xl px-4 py-2 sidebar-app-hover {{ $currentRoute === 'aplikasi.tickets.selesai' ? 'sidebar-app-active' : '' }}">
                <a href="{{ route('aplikasi.tickets.selesai') }}" class="flex items-center">
                    <i class="bi bi-check-circle mr-3 text-sm"></i> Tiket Selesai
                </a>
            </li>

            <!-- Laporan -->
            <li class="rounded-xl px-4 py-2 sidebar-app-hover {{ $currentRoute === 'aplikasi.reports' ? 'sidebar-app-active' : '' }}">
                <a href="{{ route('aplikasi.reports') }}" class="flex items-center">
                    <i class="bi bi-file-earmark-text mr-3 text-sm"></i> Laporan
                </a>
            </li>
        </ul>
    </div>

    <!-- Logout -->
    <form action="{{ route('aplikasi.logout') }}" method="POST" class="mt-6">
        @csrf
        <button type="submit" class="w-full font-medium text-xs flex items-center gap-2 border rounded-lg p-2 transition-colors" 
                style="color: #f87171; border-color: #fca5a5;" 
                onmouseover="this.style.backgroundColor='rgba(254, 226, 226, 0.1)'" 
                onmouseout="this.style.backgroundColor='transparent'">
            <i class="bi bi-box-arrow-left text-sm"></i> Keluar
        </button>
    </form>
</aside>

<!-- Bootstrap Icon & AlpineJS -->
<script src="//unpkg.com/alpinejs" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">