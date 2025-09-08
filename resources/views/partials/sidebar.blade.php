@php
    $manajemenActive = request()->routeIs('admin.users.index') || 
                       request()->routeIs('admin.aplikasi.index') || 
                       request()->routeIs('admin.kategori.index');
@endphp

<style>
    .sidebar-bg {
        background-color: #1e3a8a; /* Biru gelap seperti gambar */
    }
    .sidebar-hover:hover {
        background-color: rgba(30, 58, 138, 0.6);
    }
    .sidebar-active {
        background-color: rgba(30, 58, 138, 0.8);
    }
    .sidebar-dropdown-hover:hover {
        background-color: rgba(30, 58, 138, 0.5);
    }
</style>

<aside class="w-72 min-h-screen sidebar-bg p-6 flex flex-col justify-between">
    <div>
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10">
            <span class="text-lg font-bold text-white">Helpdesk</span>
        </div>

        <!-- Menu Navigation -->
        <ul class="space-y-3 text-white font-medium text-base">

            <!-- Dashboard -->
            <li class="rounded-xl px-4 py-2 sidebar-hover {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <i class="bi bi-columns-gap mr-3 text-sm"></i> Dashboard
                </a>
            </li>

            <!-- Tiket -->
            <li class="rounded-xl px-4 py-2 sidebar-hover {{ request()->routeIs('admin.tickets.create') ? 'sidebar-active' : '' }}">
                <a href="{{ route('admin.tickets.create') }}" class="flex items-center">
                    <i class="bi bi-plus-circle mr-3 text-sm"></i> Tambah Tiket
                </a>
            </li>

            <li class="rounded-xl px-4 py-2 sidebar-hover {{ request()->routeIs('admin.tickets.all') ? 'sidebar-active' : '' }}">
                <a href="{{ route('admin.tickets.all') }}" class="flex items-center">
                    <i class="bi bi-list-task mr-3 text-sm"></i> Semua Tiket
                </a>
            </li>
            
            <li class="rounded-xl px-4 py-2 sidebar-hover {{ request()->routeIs('admin.tickets.masuk') ? 'sidebar-active' : '' }}">
                <a href="{{ route('admin.tickets.masuk') }}" class="flex items-center">
                    <i class="bi bi-inbox-fill mr-3 text-sm"></i> Tiket Masuk
                </a>
            </li>

            <li class="rounded-xl px-4 py-2 sidebar-hover {{ request()->routeIs('admin.tickets.proses') ? 'sidebar-active' : '' }}">
                <a href="{{ route('admin.tickets.proses') }}" class="flex items-center">
                    <i class="bi bi-arrow-repeat mr-3 text-sm"></i> Tiket Proses
                </a>
            </li>

            <li class="rounded-xl px-4 py-2 sidebar-hover {{ request()->routeIs('admin.tickets.selesai') ? 'sidebar-active' : '' }}">
                <a href="{{ route('admin.tickets.selesai') }}" class="flex items-center">
                    <i class="bi bi-check-circle mr-3 text-sm"></i> Tiket Selesai
                </a>
            </li>

            <!-- Laporan -->
            <li class="rounded-xl px-4 py-2 sidebar-hover {{ request()->routeIs('admin.reports') ? 'sidebar-active' : '' }}">
                <a href="{{ route('admin.reports') }}" class="flex items-center">
                    <i class="bi bi-file-earmark-text mr-3 text-sm"></i> Laporan
                </a>
            </li>

            <!-- Manajemen Dropdown -->
            <li x-data="{ open: {{ $manajemenActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2 rounded-xl sidebar-dropdown-hover focus:outline-none"
                        :class="{ 'sidebar-active': open }">
                    <span class="flex items-center">
                        <i class="bi bi-gear-fill mr-3 text-sm"></i> Manajemen
                    </span>
                    <i class="bi text-sm" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                </button>

                <ul x-show="open" x-cloak class="pl-10 mt-2 space-y-2 text-sm">

                    <li class="{{ request()->routeIs('admin.users.index') ? 'font-bold' : '' }}" style="{{ request()->routeIs('admin.users.index') ? 'color: #dbeafe;' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2" style="color: white;" onmouseover="this.style.color='#dbeafe'" onmouseout="this.style.color='white'">
                            <i class="bi bi-person-circle text-xs"></i> Kelola Admin
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.aplikasi.index') ? 'font-bold' : '' }}" style="{{ request()->routeIs('admin.aplikasi.index') ? 'color: #dbeafe;' : '' }}">
                        <a href="{{ route('admin.aplikasi.index') }}" class="flex items-center gap-2" style="color: white;" onmouseover="this.style.color='#dbeafe'" onmouseout="this.style.color='white'">
                            <i class="bi bi-app text-xs"></i> Kelola Aplikasi
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.kategori.index') ? 'font-bold' : '' }}" style="{{ request()->routeIs('admin.kategori.index') ? 'color: #dbeafe;' : '' }}">
                        <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-2" style="color: white;" onmouseover="this.style.color='#dbeafe'" onmouseout="this.style.color='white'">
                            <i class="bi bi-tags text-xs"></i> Kelola Kategori
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
    </div>

    <!-- Tombol Logout -->
    <form action="{{ route('logout') }}" method="POST" class="mt-6">
        @csrf
        <button type="submit" class="w-full font-medium text-sm flex items-center gap-2 border rounded-lg p-2 transition-colors" 
                style="color: #f87171; border-color: #fca5a5;" 
                onmouseover="this.style.backgroundColor='rgba(254, 226, 226, 0.1)'" 
                onmouseout="this.style.backgroundColor='transparent'">
            <i class="bi bi-box-arrow-left text-base"></i> Keluar
        </button>
    </form>
</aside>

<!-- Tambahkan AlpineJS jika belum ada -->
<script src="//unpkg.com/alpinejs" defer></script>