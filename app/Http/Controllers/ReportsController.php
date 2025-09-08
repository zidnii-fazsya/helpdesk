<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        // Total semua tiket
        $totalTiket   = Ticket::count();

        // Tiket dengan status "Selesai"
        $tiketSelesai = Ticket::where('status', 'Selesai')->count();

        // Tiket dengan status "Proses"
        $tiketProses  = Ticket::where('status', 'Proses')->count();

        // Tiket selesai untuk ditampilkan di laporan
        $tickets = Ticket::where('status', 'Selesai')
                         ->orderBy('tanggal', 'desc')
                         ->get();

        // Tahun-tahun unik dari tanggal tiket
        $years = Ticket::whereNotNull('tanggal')
                       ->selectRaw('YEAR(tanggal) as year')
                       ->distinct()
                       ->orderBy('year', 'desc')
                       ->pluck('year');

        return view('admin.reports.index', compact(
            'totalTiket',
            'tiketSelesai',
            'tiketProses',
            'tickets',
            'years'
        ));
    }

    public function exportBulanan(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year'  => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $bulanNama = $request->input('month');
        $tahun     = $request->input('year');

        $bulanMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3,
            'April' => 4, 'Mei' => 5, 'Juni' => 6,
            'Juli' => 7, 'Agustus' => 8, 'September' => 9,
            'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];

        if (!array_key_exists($bulanNama, $bulanMap)) {
            return back()->withErrors(['month' => 'Nama bulan tidak valid.']);
        }

        $bulan = $bulanMap[$bulanNama];

        $tickets = Ticket::with('aplikasi')
                         ->whereMonth('tanggal', $bulan)
                         ->whereYear('tanggal', $tahun)
                         ->where('status', 'Selesai')
                         ->get();

        $aplikasiName = 'Semua Aplikasi'; // Bisa disesuaikan jika filtering per aplikasi
        $tipe = 'bulanan';

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'tickets',
            'aplikasiName',
            'tipe',
            'bulan',
            'tahun'
        ));

        return $pdf->download("laporan-bulanan-{$bulanNama}-{$tahun}.pdf");
    }

    public function exportTahunan(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $tahun = $request->input('year');

        $tickets = Ticket::with('aplikasi')
                         ->whereYear('tanggal', $tahun)
                         ->where('status', 'Selesai')
                         ->get();

        $aplikasiName = 'Semua Aplikasi'; // Bisa disesuaikan jika filtering per aplikasi
        $tipe = 'tahunan';
        $bulan = null; // tidak digunakan di tampilan PDF

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'tickets',
            'aplikasiName',
            'tipe',
            'bulan',
            'tahun'
        ));

        return $pdf->download("laporan-tahunan-{$tahun}.pdf");
    }
}
