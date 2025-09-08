{{-- resources/views/partials/navbar.blade.php --}}

<style>
    .navbar-bg {
        background-color: #1e3a8a !important;
        height: 64px; /* Fixed height untuk navbar */
    }
    .navbar-hover:hover {
        background-color: #1d4ed8 !important;
    }
    .navbar-shadow {
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
    }
    .navbar-container {
        max-height: 64px;
        min-height: 64px;
    }
</style>

<div class="navbar-bg navbar-shadow navbar-container">
    <div class="flex justify-between items-center px-6 py-3 h-full">
        <h1 class="text-base font-medium text-white truncate max-w-md">
            @yield('page-title', 'Kementerian Luar Negeri Republik Indonesia')
        </h1>
        
        <div class="flex items-center gap-3">
            <!-- Notification Icon -->
            <button class="navbar-hover p-2 rounded-full transition-colors duration-200 relative">
                <i class="bi bi-bell text-white text-base"></i>
                <!-- Notification Badge (optional) -->
                {{-- <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center text-xs">3</span> --}}
            </button>
            
            <!-- User Profile -->
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/user.jpeg') }}" alt="User" class="w-8 h-8 rounded-full border-2 border-white object-cover">
                <div class="text-white text-xs">
                    <p class="font-medium leading-tight">{{ auth()->user()->name ?? 'Zidnii Lu\'lu Fazsya' }}</p>
                    <p class="text-blue-100 text-xs leading-tight">{{ auth()->user()->role ?? 'admin' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>