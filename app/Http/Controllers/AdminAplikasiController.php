<?php

namespace App\Http\Controllers;

use App\Models\{Ticket, User, Aplikasi, ProgressTicket};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;

class AdminAplikasiController extends Controller
{
    /**
     * Ambil data admin & aplikasi yang sedang login.
     */
    private function getAdminAplikasi(): array
    {
        $admin = auth()->user();

        if (!$admin || !Str::startsWith(strtolower(trim($admin->role)), 'admin aplikasi')) {
            abort(403, 'Unauthorized - Anda bukan admin aplikasi');
        }

        $aplikasiName = trim(Str::after(strtolower($admin->role), 'admin aplikasi'));
        $aplikasi = Aplikasi::whereRaw('LOWER(nama_aplikasi) = ?', [$aplikasiName])->firstOrFail();

        return compact('admin', 'aplikasi', 'aplikasiName');
    }

    /**
     * Dashboard admin aplikasi
     */
    public function index(): View
    {
        extract($this->getAdminAplikasi());

        $baseQuery = Ticket::where('aplikasi_id', $aplikasi->id);

        $totalTiketMasuk   = (clone $baseQuery)->count();
        $tiketSedangProses = (clone $baseQuery)->where('status', 'Proses')->count();
        $tiketSelesai      = (clone $baseQuery)->where('status', 'Selesai')->count();

        $grafikData = [];
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        for ($i = 11; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subMonths($i);
            $jumlah = (clone $baseQuery)
                ->whereMonth('created_at', $tanggal->month)
                ->whereYear('created_at', $tanggal->year)
                ->where('status', 'Selesai')
                ->count();

            $grafikData[] = [
                'bulan'       => $bulanNames[$tanggal->month],
                'jumlah'      => $jumlah,
                'bulan_angka' => $tanggal->month,
                'tahun'       => $tanggal->year
            ];
        }

        $tickets  = (clone $baseQuery)->latest()->limit(10)->get();
        $teknisis = User::where('role', 'teknisi')->get();

        return view('admin_aplikasi.dashboard', compact(
            'tickets', 'teknisis', 'aplikasiName',
            'totalTiketMasuk', 'tiketSedangProses', 'tiketSelesai', 'grafikData'
        ));
    }

    /**
     * Semua tiket
     */
    public function tickets(Request $request): View
    {
        extract($this->getAdminAplikasi());

        $query = Ticket::where('aplikasi_id', $aplikasi->id);

        if ($request->filled('nama')) {
            $search = $request->nama;
            $query->where(function ($q) use ($search) {
                $q->where('reporter_name', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('admin_aplikasi.tickets', compact('tickets', 'aplikasiName'));
    }

    /**
     * Tiket masuk
     */
    public function tiketMasuk(Request $request): View
    {
        extract($this->getAdminAplikasi());

        $query = Ticket::where('aplikasi_id', $aplikasi->id)->where('status', 'Masuk');

        if ($request->filled('nama')) {
            $query->where('reporter_name', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('admin_aplikasi.tickets.masuk', compact('tickets', 'aplikasiName'));
    }

    /**
     * Ambil tiket
     */
    public function ambilTiket($id): RedirectResponse
    {
        extract($this->getAdminAplikasi());

        $ticket = Ticket::findOrFail($id);

        if ($ticket->aplikasi_id !== $aplikasi->id) {
            return back()->with('error', 'Tiket ini bukan milik aplikasi Anda.');
        }

        $ticket->update([
            'status'           => 'Proses',
            'teknisi_nama'     => $admin->name,
            'teknisi_nip'      => $admin->nip ?? '-',
            'teknisi_kategori' => $aplikasi->nama_aplikasi,
        ]);

        // Tambahkan histori progres
        $ticket->progress()->create([
            'narasi'             => "Tiket diambil oleh {$admin->name}",
            'waktu_progres'      => now(),
            'admin_aplikasi_id'  => $admin->id,
        ]);

        return redirect()->route('aplikasi.tickets.progress.form', $ticket->id)
            ->with('success', 'Tiket berhasil diambil. Silakan isi progres.');
    }

    /**
     * Tiket proses
     */
    public function tiketProses(Request $request): View
    {
        extract($this->getAdminAplikasi());

        $query = Ticket::where('aplikasi_id', $aplikasi->id)->where('status', 'Proses');

        if ($request->filled('nama')) {
            $query->where('reporter_name', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('admin_aplikasi.tickets.proses', compact('tickets', 'aplikasiName'));
    }

    /**
     * Tiket selesai
     */
    public function tiketSelesai(Request $request): View
    {
        extract($this->getAdminAplikasi());

        $query = Ticket::where('aplikasi_id', $aplikasi->id)->where('status', 'Selesai');

        if ($request->filled('nama')) {
            $query->where('reporter_name', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $tickets = $query->with('progress.adminAplikasi')->latest()->paginate(15)->withQueryString();

        return view('admin_aplikasi.tickets.selesai', compact('tickets', 'aplikasiName'));
    }

    /**
     * Assign teknisi
     */
    public function assignTeknisi(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'teknisi_nama' => 'required|string',
            'teknisi_nip'  => 'required|string',
            'status'       => 'required|in:Proses,Selesai'
        ]);

        extract($this->getAdminAplikasi());

        $ticket = Ticket::findOrFail($id);

        if ($ticket->aplikasi_id !== $aplikasi->id) {
            return back()->with('error', 'Anda tidak dapat assign tiket untuk aplikasi lain.');
        }

        $ticket->update([
            'teknisi_nama'     => $request->teknisi_nama,
            'teknisi_nip'      => $request->teknisi_nip,
            'teknisi_kategori' => $aplikasi->nama_aplikasi,
            'status'           => $request->status,
        ]);

        return redirect()->route('aplikasi.tickets.proses')
            ->with('success', 'Teknisi berhasil ditetapkan.');
    }

    /**
     * Form progres tiket
     */
    public function showProgresForm($ticketId): View
    {
        extract($this->getAdminAplikasi());

        $ticket = Ticket::with('progress')->findOrFail($ticketId);

        if ($ticket->aplikasi_id !== $aplikasi->id) {
            abort(403, 'Anda tidak dapat mengakses tiket dari aplikasi lain.');
        }

        return view('admin_aplikasi.tickets.progres', compact('ticket', 'aplikasiName'));
    }

    /**
     * Simpan progres
     */
    public function storeProgres(Request $request, $ticketId): RedirectResponse
    {
        $request->validate([
            'narasi'        => 'required|string',
            'waktu_progres' => 'required',
        ]);

        extract($this->getAdminAplikasi());

        $ticket = Ticket::findOrFail($ticketId);

        if ($ticket->aplikasi_id !== $aplikasi->id) {
            abort(403, 'Anda tidak dapat menyimpan progres untuk tiket dari aplikasi lain.');
        }

        $ticket->progress()->create([
            'narasi'             => $request->narasi,
            'waktu_progres'      => $request->waktu_progres,
            'admin_aplikasi_id'  => auth()->id(),
        ]);

        if ($ticket->status === 'Masuk') {
            $ticket->update(['status' => 'Proses']);
        }

        return back()->with('success', 'Progres berhasil disimpan.');
    }

    /**
     * Halaman laporan
     */
    public function laporan(Request $request): View
    {
        extract($this->getAdminAplikasi());

        $tickets = Ticket::where('aplikasi_id', $aplikasi->id)->latest()->get();

        $years = Ticket::where('aplikasi_id', $aplikasi->id)
            ->selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->pluck('year');

        $totalTiket   = Ticket::where('aplikasi_id', $aplikasi->id)->count();
        $tiketProses  = Ticket::where('aplikasi_id', $aplikasi->id)->where('status', 'Proses')->count();
        $tiketSelesai = Ticket::where('aplikasi_id', $aplikasi->id)->where('status', 'Selesai')->count();

        return view('admin_aplikasi.reports.index', compact(
            'tickets', 'aplikasiName', 'years',
            'totalTiket', 'tiketProses', 'tiketSelesai'
        ));
    }

    /**
     * Export laporan bulanan
     */
    public function exportBulanan(Request $request)
    {
        extract($this->getAdminAplikasi());

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $tickets = Ticket::where('aplikasi_id', $aplikasi->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'Selesai')
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('admin_aplikasi.reports.export_pdf', compact(
            'tickets', 'aplikasiName', 'bulan', 'tahun'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-bulanan-{$aplikasiName}-{$bulan}-{$tahun}.pdf");
    }

    /**
     * Export laporan tahunan
     */
    public function exportTahunan(Request $request)
    {
        extract($this->getAdminAplikasi());

        $tahun = $request->tahun;

        $tickets = Ticket::where('aplikasi_id', $aplikasi->id)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'Selesai')
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('admin_aplikasi.reports.export_pdf', compact(
            'tickets', 'aplikasiName', 'tahun'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-tahunan-{$aplikasiName}-{$tahun}.pdf");
    }
}
