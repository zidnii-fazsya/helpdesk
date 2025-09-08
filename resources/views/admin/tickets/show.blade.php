@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Tiket</h1>

    {{-- Informasi Tiket --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Informasi Tiket</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th style="width: 200px;">Nomor Tiket</th>
                    <td>{{ $ticket->ticket_number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $ticket->tanggal ? $ticket->tanggal->format('d-m-Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Pelapor</th>
                    <td>{{ $ticket->reporter_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $ticket->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Ruangan</th>
                    <td>{{ $ticket->ruangan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>
                        @if($ticket->kategoriTickets && $ticket->kategoriTickets->count() > 0)
                            {{ $ticket->kategoriTickets->pluck('nama_kategori')->join(', ') }}
                        @else
                            {{ $ticket->kategori ?? '-' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Aplikasi</th>
                    <td>{{ $ticket->aplikasi->nama_aplikasi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ strtolower($ticket->status) === 'selesai' ? 'success' : (strtolower($ticket->status) === 'proses' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($ticket->status ?? '-') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Teknisi</th>
                    <td>
                        @if($ticket->teknisi_nama)
                            {{ $ticket->teknisi_nama }} ({{ $ticket->teknisi_nip }})
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Satuan Kerja</th>
                    <td>{{ $ticket->satuan_kerja ?? '-' }}</td>
                </tr>
                {{-- Informasi Prioritas --}}
                <tr>
                    <th>Prioritas</th>
                    <td>{{ ucfirst($ticket->prioritas ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Keterangan Prioritas</th>
                    <td>
                        @if(!empty($ticket->keterangan_prioritas))
                            {!! nl2br(e($ticket->keterangan_prioritas)) !!}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Narasi Keluhan --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
            <strong>Narasi Keluhan</strong>
        </div>
        <div class="card-body">
            @if(!empty($ticket->keluhan))
                {!! nl2br(e($ticket->keluhan)) !!}
            @else
                <span class="text-muted">Tidak ada keluhan yang ditambahkan.</span>
            @endif
        </div>
    </div>

    {{-- Riwayat Progress --}}
    @if($ticket->progresses && $ticket->progresses->count() > 0)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <strong>Riwayat Progress</strong>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ticket->progresses as $progress)
                            <tr>
                                <td>{{ $progress->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $progress->keterangan ?? '-' }}</td>
                                <td>{{ $progress->dibuat_oleh ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Tombol Kembali --}}
    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
@endsection
