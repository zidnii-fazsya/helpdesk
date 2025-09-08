<body>
    @include('partials.navbar')

    <main class="p-4">
        {{-- Flash Message Area --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-500 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
