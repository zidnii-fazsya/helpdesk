<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Helpdesk KEMLU</title>

    {{-- CSS Vite (pastikan kamu sudah jalanin vite dev / build) --}}
    @vite('resources/css/app.css')

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Konten Utama --}}
    <div class="flex-1 flex flex-col">
        {{-- Navbar --}}
        @include('partials.navbar')

        {{-- Main Content --}}
        <main class="p-6 flex-1 overflow-y-auto">
            {{-- Page Title (Opsional) --}}
            @hasSection('page-title')
                <h1 class="text-2xl font-bold mb-4">@yield('page-title')</h1>
            @endif

            {{-- Content --}}
            @yield('content')
        </main>
    </div>

</body>
</html>
