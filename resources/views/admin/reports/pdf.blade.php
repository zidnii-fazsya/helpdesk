<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tiket - {{ ucwords($aplikasiName) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>

    <h2>Laporan Tiket Selesai - Aplikasi {{ ucwords($aplikasiName) }}</h2>

    @if(isset($tipe) && $tipe === 'bulanan')
        <h3>Bulan {{ \DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</h3>
    @elseif(isset($tipe) && $tipe === 'tahunan')
        <h3>Tahun {{ $tahun }}</h3>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No Tiket</th>
                <th>Keluhan</th>
                <th>Nama Aplikasi</th>
                <th>Status</th>
                <th>Ditangani Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $index => $ticket)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($ticket->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $ticket->ticket_number }}</td>
                    <td>{{ $ticket->keluhan }}</td>
                    <td>{{ $ticket->aplikasi->nama_aplikasi ?? '-' }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->diambil_oleh ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data tiket selesai untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
