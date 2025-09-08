<?php

namespace App\Http\Controllers;

use App\Models\{Ticket, Kategori, Aplikasi, ProgressTicket, SubKategori};
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /** ================= CREATE ================= */
    public function create(): View
    {
        // Ambil kategori dengan subkategorinya agar bisa ditampilkan di Blade
        $kategoris     = Kategori::with('subkategoris')->orderBy('nama_kategori')->get();
        $aplikasis     = Aplikasi::orderBy('nama_aplikasi')->get();
        $prioritasList = ['tinggi', 'sedang', 'rendah'];

        return view('admin.tickets.create', compact('kategoris', 'aplikasis', 'prioritasList'));
    }

    /** ================= STORE ================= */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal'              => ['required', 'date'],
            'reporter_name'        => ['required', 'string', 'max:255'],
            'jabatan'              => ['required', 'string', 'max:255'],
            'ruangan'              => ['required', 'string', 'max:255'],
            'satuan_kerja'         => ['required', 'string', 'max:255'],
            'kategori_id'          => ['required', 'integer', 'exists:kategoris,id'],
            'subkategori_id'       => ['nullable', 'array'],
            'subkategori_id.*'     => ['integer'], // Allow both subkategori and aplikasi IDs
            'keluhan'              => ['required', 'string'],
            'prioritas'            => ['required', 'in:tinggi,sedang,rendah'],
            'waktu_respon'         => ['nullable', 'integer', 'min:0'],
            'sla'                  => ['nullable', 'integer', 'min:0'],
            'eskalasi'             => ['nullable', 'string', 'max:255'],
            'keterangan_prioritas' => ['nullable', 'string'],
        ]);

        // Generate nomor tiket unik
        $ticketNumber = 'TKT-' . now()->format('YmdHis');

        // Ambil nama kategori utama
        $kategori = Kategori::find($request->kategori_id);
        $kategoriUtama = $kategori ? $kategori->nama_kategori : '-';

        $aplikasiId = null;
        $subkategoriIds = [];

        if ($kategoriUtama === 'Perangkat Lunak') {
            $aplikasiId = $request->subkategori_id[0] ?? null;
        } else {
            $subkategoriIds = $request->subkategori_id ?? [];
        }

        // Simpan tiket
        $ticket = Ticket::create([
            'ticket_number'        => $ticketNumber,
            'tanggal'              => $request->tanggal,
            'reporter_name'        => $request->reporter_name,
            'jabatan'              => $request->jabatan,
            'ruangan'              => $request->ruangan,
            'satuan_kerja'         => $request->satuan_kerja,
            'kategori'             => $kategoriUtama,
            'keluhan'              => $request->keluhan,
            'status'               => 'Masuk',
            'prioritas'            => $request->prioritas,
            'waktu_respon'         => $request->waktu_respon,
            'sla'                  => $request->sla,
            'eskalasi'             => $request->eskalasi,
            'keterangan_prioritas' => $request->keterangan_prioritas,
            'aplikasi_id'          => $aplikasiId,
        ]);

        // Sinkronisasi kategori (jika ada relasi many-to-many)
        $ticket->kategoriTickets()->sync([$request->kategori_id]);

        // Sinkronisasi subkategori
        if (!empty($subkategoriIds)) {
            $ticket->subkategoris()->sync($subkategoriIds);
        }

        return redirect()
            ->route('admin.tickets.masuk')
            ->with('success', 'Tiket berhasil ditambahkan.');
    }

    /** ================= EDIT ================= */
    public function edit($id): View
    {
        $ticket        = Ticket::with(['kategoriTickets', 'subkategoris'])->findOrFail($id);
        $kategoris     = Kategori::with('subkategoris')->orderBy('nama_kategori')->get();
        $aplikasis     = Aplikasi::orderBy('nama_aplikasi')->get();
        $prioritasList = ['tinggi', 'sedang', 'rendah'];
        $kategori_id = $ticket->kategoriTickets->first()->id;

        return view('admin.tickets.edit', compact('ticket', 'kategoris', 'aplikasis', 'prioritasList', 'kategori_id'));
    }

    /** ================= UPDATE ================= */
    public function update(Request $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status !== 'Masuk') {
            return redirect()->route('admin.tickets.masuk')
                ->with('error', 'Tiket sudah diproses atau selesai, tidak dapat diedit lagi.');
        }

        $request->validate([
            'tanggal'              => ['required', 'date'],
            'reporter_name'        => ['required', 'string', 'max:255'],
            'jabatan'              => ['required', 'string', 'max:255'],
            'ruangan'              => ['required', 'string', 'max:255'],
            'satuan_kerja'         => ['required', 'string', 'max:255'],
            'kategori_id'          => ['required', 'integer', 'exists:kategoris,id'],
            'subkategori_id'       => ['nullable', 'array'],
            'subkategori_id.*'     => ['integer'], // Allow both subkategori and aplikasi IDs
            'keluhan'              => ['required', 'string'],
            'prioritas'            => ['required', 'in:tinggi,sedang,rendah'],
            'waktu_respon'         => ['nullable', 'integer', 'min:0'],
            'sla'                  => ['nullable', 'integer', 'min:0'],
            'eskalasi'             => ['nullable', 'string', 'max:255'],
            'keterangan_prioritas' => ['nullable', 'string'],
        ]);

        $kategori = Kategori::find($request->kategori_id);
        $kategoriUtama = $kategori ? $kategori->nama_kategori : '-';

        $aplikasiId = null;
        $subkategoriIds = [];

        if ($kategoriUtama === 'Perangkat Lunak') {
            $aplikasiId = $request->subkategori_id[0] ?? null;
        } else {
            $subkategoriIds = $request->subkategori_id ?? [];
        }

        $ticket->update([
            'tanggal'              => $request->tanggal,
            'reporter_name'        => $request->reporter_name,
            'jabatan'              => $request->jabatan,
            'ruangan'              => $request->ruangan,
            'satuan_kerja'         => $request->satuan_kerja,
            'kategori'             => $kategoriUtama,
            'keluhan'              => $request->keluhan,
            'prioritas'            => $request->prioritas,
            'waktu_respon'         => $request->waktu_respon,
            'sla'                  => $request->sla,
            'eskalasi'             => $request->eskalasi,
            'keterangan_prioritas' => $request->keterangan_prioritas,
            'aplikasi_id'          => $aplikasiId,
        ]);

        $ticket->kategoriTickets()->sync([$request->kategori_id]);

        if ($kategoriUtama === 'Perangkat Lunak') {
            $ticket->subkategoris()->sync([]);
        } else {
            if (!empty($subkategoriIds)) {
                $ticket->subkategoris()->sync($subkategoriIds);
            }
        }

        return redirect()
            ->route('admin.tickets.masuk')
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    /** ================= SHOW ================= */
    public function show(Ticket $ticket)
    {
        $ticket->load(['progresses.adminAplikasi', 'aplikasi', 'subkategoris']);

        return view('admin.tickets.show', compact('ticket'));
    }

    /** ================= LISTING & FILTER ================= */
    public function all(Request $request): View
    {
        $tickets = $this->applyFilters($request)
            ->with(['progresses.adminAplikasi', 'aplikasi', 'subkategoris'])
            ->paginate(10);

        return view('admin.tickets.all', compact('tickets'));
    }

    private function applyFilters(Request $request)
    {
        $query = Ticket::with(['aplikasi', 'latestProgress', 'progresses.adminAplikasi', 'subkategoris']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('reporter_name', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhere('keluhan', 'like', "%{$search}%")
                    ->orWhere('satuan_kerja', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $status = ucfirst(strtolower($request->status));
            if (in_array($status, ['Masuk', 'Proses', 'Selesai'])) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('prioritas') && in_array($request->prioritas, ['tinggi','sedang','rendah'])) {
            $query->where('prioritas', $request->prioritas);
        }

        return $query->orderByRaw("FIELD(prioritas, 'tinggi', 'sedang', 'rendah')")
            ->orderByDesc('created_at');
    }

    private function filterByStatus(Request $request, string $status): View
    {
        $tickets = Ticket::with(['aplikasi', 'latestProgress', 'progresses.adminAplikasi', 'subkategoris'])
            ->where('status', $status)
            ->orderByRaw("FIELD(prioritas, 'tinggi', 'sedang', 'rendah')")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view("admin.tickets." . strtolower($status), compact('tickets'));
    }

    public function masuk(Request $request): View
    {
        return $this->filterByStatus($request, 'Masuk');
    }

    public function proses(Request $request): View
    {
        return $this->filterByStatus($request, 'Proses');
    }

    public function selesai(Request $request): View
    {
        return $this->filterByStatus($request, 'Selesai');
    }

    /** ================= REPORT ================= */
    public function reports(): View
    {
        $tickets = Ticket::with(['aplikasi', 'latestProgress', 'progresses.adminAplikasi', 'subkategoris'])
            ->orderByRaw("FIELD(prioritas, 'tinggi', 'sedang', 'rendah')")
            ->orderByDesc('created_at')
            ->get();

        return view('admin.reports.index', compact('tickets'));
    }

    /** ================= STATUS UPDATE ================= */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:Masuk,Proses,Selesai']]);

        $ticket = Ticket::findOrFail($id);
        $status = ucfirst(strtolower($request->status));

        $ticket->update(['status' => $status]);

        $ticket->progresses()->create([
            'narasi'            => "Status tiket diubah menjadi {$status} oleh " . (Auth::user()->name ?? 'System'),
            'waktu_progres'     => now(),
            'admin_aplikasi_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    /** ================= DELETE ================= */
    public function destroy($id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status === 'Selesai') {
            return redirect()->back()->with('error', 'Tiket yang sudah selesai tidak dapat dihapus.');
        }

        $ticket->delete();
        return redirect()->back()->with('success', 'Tiket berhasil dihapus.');
    }

    /** ================= AMBIL TIKET ================= */
    public function ambil(Request $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status !== 'Masuk') {
            return redirect()->back()->with('error', 'Tiket sudah diproses, tidak bisa diambil lagi.');
        }

        $ticket->update([
            'diambil_oleh' => Auth::user()->name ?? '-',
            'status'       => 'Proses'
        ]);

        $ticket->progresses()->create([
            'narasi'            => "Tiket diambil oleh " . (Auth::user()->name ?? 'Admin'),
            'waktu_progres'     => now(),
            'admin_aplikasi_id' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.tickets.proses')
            ->with('success', 'Tiket berhasil diambil.');
    }

    /** ================= SIMPAN PROGRESS ================= */
    public function storeProgress(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'narasi'        => ['required', 'string'],
            'waktu_progres' => ['required', 'date'],
        ]);

        $ticket = Ticket::findOrFail($id);

        $ticket->progresses()->create([
            'narasi'            => $request->narasi,
            'waktu_progres'     => $request->waktu_progres,
            'admin_aplikasi_id' => Auth::id(),
        ]);

        if ($ticket->status === 'Masuk') {
            $ticket->update(['status' => 'Proses']);
        }

        return redirect()
            ->route('admin.tickets.proses')
            ->with('success', 'Progres tiket berhasil disimpan.');
    }

    /** ================= GET PROGRESS VIEW ================= */
    public function getProgress(Ticket $ticket): View
    {
        $progresses = $ticket->progresses()
            ->with('adminAplikasi')
            ->orderBy('waktu_progres')
            ->get();

        return view('admin.aplikasi.components.progres_popup', compact('ticket', 'progresses'));
    }
}
