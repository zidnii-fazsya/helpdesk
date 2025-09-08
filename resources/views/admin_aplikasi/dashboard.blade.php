<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Aplikasi {{ ucwords($aplikasiName) }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body {
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--bg-color), var(--bg-color-dark));
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-card-masuk {
            --bg-color: #3b82f6;
            --bg-color-dark: #1d4ed8;
        }

        .stat-card-proses {
            --bg-color: #10b981;
            --bg-color-dark: #059669;
        }

        .stat-card-selesai {
            --bg-color: #f59e0b;
            --bg-color-dark: #d97706;
        }

        .table-header-blue {
            background-color: #3b82f6;
            color: white;
        }

        .status-masuk {
            background-color: #3b82f6;
            color: white;
        }

        .status-proses {
            background-color: #10b981;
            color: white;
        }

        .status-selesai {
            background-color: #f59e0b;
            color: white;
        }

        .prioritas-tinggi {
            background-color: #ef4444;
            color: white;
        }

        .prioritas-sedang {
            background-color: #f59e0b;
            color: white;
        }

        .prioritas-rendah {
            background-color: #10b981;
            color: white;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="flex min-h-screen bg-white">
    @include('partials.sidebar-aplikasi')

    <div class="flex-1 flex flex-col">
        @include('partials.navbar')

        <main class="flex-1 p-8 bg-white">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="bi bi-check-circle-fill mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card stat-card-masuk p-6 text-white relative">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-4xl font-bold mb-2">{{ $totalTiketMasuk }}</div>
                            <div class="text-sm opacity-90">Total Tiket Masuk</div>
                            <div class="text-xs opacity-75">{{ $totalTiketMasuk }} Tiket</div>
                        </div>
                        <div class="text-5xl opacity-20">
                            <i class="bi bi-inbox-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card stat-card-proses p-6 text-white relative">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-4xl font-bold mb-2">{{ $tiketSedangProses }}</div>
                            <div class="text-sm opacity-90">Tiket Sedang Proses</div>
                            <div class="text-xs opacity-75">{{ $tiketSedangProses }} Tiket</div>
                        </div>
                        <div class="text-5xl opacity-20">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card stat-card-selesai p-6 text-white relative">
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-4xl font-bold mb-2">{{ $tiketSelesai }}</div>
                            <div class="text-sm opacity-90">Tiket Selesai</div>
                            <div class="text-xs opacity-75">{{ $tiketSelesai }} Tiket</div>
                        </div>
                        <div class="text-5xl opacity-20">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Tiket Masuk Terbaru -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h3 class="text-lg font-medium text-white">Tiket Masuk Terbaru</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-800">
                        <thead class="table-header-blue">
                            <tr class="border-b border-blue-800">
                                <th class="px-6 py-4 font-semibold text-white">No</th>
                                <th class="px-6 py-4 font-semibold text-white">Nama Pelapor</th>
                                <th class="px-6 py-4 font-semibold text-white">Jabatan</th>
                                <th class="px-6 py-4 font-semibold text-white">No. Tiket</th>
                                <th class="px-6 py-4 font-semibold text-white">Tanggal</th>
                                <th class="px-6 py-4 font-semibold text-white">Kategori</th>
                                <th class="px-6 py-4 font-semibold text-white">Prioritas</th> <!-- Tambahan kolom Prioritas -->
                                <th class="px-6 py-4 font-semibold text-white">Keluhan</th>
                                <th class="px-6 py-4 font-semibold text-white">Ruangan</th>
                                <th class="px-6 py-4 font-semibold text-white">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets ?? [] as $index => $ticket)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $ticket->reporter_name }}</td>
                                    <td class="px-6 py-4">{{ $ticket->jabatan }}</td>
                                    <td class="px-6 py-4">{{ $ticket->ticket_number }}</td>
                                    <td class="px-6 py-4">{{ $ticket->tanggal }}</td>
                                    <td class="px-6 py-4">{{ $ticket->kategori }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $prioritasClass = match($ticket->prioritas) {
                                                'tinggi' => 'prioritas-tinggi',
                                                'sedang' => 'prioritas-sedang',
                                                'rendah' => 'prioritas-rendah',
                                                default => 'bg-gray-500 text-white',
                                            };
                                        @endphp
                                        <span class="text-xs px-3 py-1 font-semibold rounded-full transition {{ $prioritasClass }}">
                                            {{ ucfirst($ticket->prioritas) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ \Illuminate\Support\Str::limit($ticket->keluhan, 50) }}</td>
                                    <td class="px-6 py-4">{{ $ticket->ruangan }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClass = match($ticket->status) {
                                                'Masuk' => 'status-masuk',
                                                'Proses' => 'status-proses',
                                                'Selesai' => 'status-selesai',
                                                default => 'bg-gray-500 text-white',
                                            };
                                        @endphp
                                        <span class="text-xs px-3 py-1 font-semibold rounded-full transition {{ $statusClass }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-12 text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-ticket-perforated-fill text-4xl mb-2"></i>
                                            <p class="text-lg font-semibold">Belum ada tiket masuk</p>
                                            <p class="text-sm">Tiket yang masuk akan ditampilkan di sini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grafik Tiket Bulanan -->
            @if(count($grafikData) > 0 && collect($grafikData)->sum('jumlah') > 0)
                <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Persentase Tiket Masuk Bulanan</h2>
                    <div class="chart-container h-64">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Persentase Tiket Masuk Bulanan</h2>
                    <div class="text-center text-gray-400 py-12">
                        <div class="flex flex-col items-center">
                            <i class="bi bi-bar-chart-line-fill text-4xl mb-2"></i>
                            <p class="text-lg font-semibold">Belum ada data untuk grafik</p>
                            <p class="text-sm">Grafik akan muncul setelah ada tiket masuk</p>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>

<script>
    const grafikData = @json($grafikData);
    if (grafikData.length && grafikData.reduce((a, b) => a + b.jumlah, 0) > 0) {
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: grafikData.map(item => item.bulan),
                datasets: [{
                    label: 'Tiket Selesai',
                    data: grafikData.map(item => item.jumlah),
                    backgroundColor: [
                        '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
                        '#8b5cf6', '#06b6d4', '#6b7280', '#14b8a6',
                        '#22c55e', '#eab308', '#f97316', '#84cc16'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { stepSize: 1, color: '#6b7280' }
                    }
                }
            });
    }
</script>
</body>
</html>
