<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Aplikasi</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .active-menu {
            background-color: #93c5fd; /* #bfdbfe bg-blue-300 */
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen">
        @include('partials.sidebar-aplikasi')
            {{-- Konten Utama --}}
    <div class="flex-1 flex flex-col">
        {{-- Navbar --}}
        @include('partials.navbar-aplikasi')

            <main class="flex-1 bg-white">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
