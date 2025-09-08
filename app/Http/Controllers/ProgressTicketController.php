<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressTicketController extends Controller
{
    /**
     * ---------------------------------------------------------------------
     *  Tampilkan halaman form progres + histori progres
     * ---------------------------------------------------------------------
     */
    public function showForm(Ticket $ticket)
    {
        $user = Auth::user();

        // ------------------------------------------------------------------
        // Validasi: hanya admin aplikasi yang berhak akses
        // ------------------------------------------------------------------
        if (!str_starts_with(strtolower($user->role), 'admin aplikasi')) {
            abort(403, 'Akses ditolak. Anda bukan admin aplikasi.');
        }

        // ------------------------------------------------------------------
        // Ambil nama aplikasi dari role
        // Contoh: "admin aplikasi keuangan" → "keuangan"
        // ------------------------------------------------------------------
        $namaAplikasi = strtolower(
            trim(str_replace('admin aplikasi', '', $user->role))
        );

        // ------------------------------------------------------------------
        // Cek apakah aplikasi tiket sesuai dengan role admin aplikasi
        // ------------------------------------------------------------------
        if (strtolower(optional($ticket->aplikasi)->nama_aplikasi) !== $namaAplikasi) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        // ------------------------------------------------------------------
        // Load relasi histori progres beserta nama admin aplikasi
        // ------------------------------------------------------------------
        $ticket->load(['progresses.adminAplikasi']);

        // ------------------------------------------------------------------
        // Kirim ke view progres tiket
        // ------------------------------------------------------------------
        return view('admin_aplikasi.tickets.progres', compact('ticket'));
    }

    /**
     * ---------------------------------------------------------------------
     *  Simpan progres baru atau selesaikan tiket
     * ---------------------------------------------------------------------
     */
    public function store(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // ------------------------------------------------------------------
        // Validasi: hanya admin aplikasi yang berhak akses
        // ------------------------------------------------------------------
        if (!str_starts_with(strtolower($user->role), 'admin aplikasi')) {
            abort(403, 'Akses ditolak. Anda bukan admin aplikasi.');
        }

        // ------------------------------------------------------------------
        // Ambil nama aplikasi dari role
        // Contoh: "admin aplikasi keuangan" → "keuangan"
        // ------------------------------------------------------------------
        $namaAplikasi = strtolower(
            trim(str_replace('admin aplikasi', '', $user->role))
        );

        // ------------------------------------------------------------------
        // Cek apakah aplikasi tiket sesuai dengan role admin aplikasi
        // ------------------------------------------------------------------
        if (strtolower(optional($ticket->aplikasi)->nama_aplikasi) !== $namaAplikasi) {
            abort(403, 'Anda tidak memiliki akses untuk progres tiket ini.');
        }

        // ------------------------------------------------------------------
        // Validasi input dari form
        // ------------------------------------------------------------------
        $request->validate([
            'narasi' => 'required|string|max:1000',
            'status' => 'nullable|string|in:proses,selesai',
        ]);

        // ------------------------------------------------------------------
        // Simpan progres ke database
        // Gunakan datetime penuh, bukan hanya jam-menit
        // ------------------------------------------------------------------
        $ticket->progresses()->create([
            'narasi'            => $request->narasi,
            'waktu_progres'     => now(), // full datetime
            'admin_aplikasi_id' => $user->id,
        ]);

        // ------------------------------------------------------------------
        // Update status tiket
        // ------------------------------------------------------------------
        if ($request->status === 'selesai') {

            // Jika status tiket diselesaikan
            $ticket->update(['status' => 'Selesai']);
            $pesan = 'Tiket berhasil diselesaikan.';

        } else {

            // Jika tiket awalnya "Masuk" maka ubah ke "Proses"
            if ($ticket->status === 'Masuk') {
                $ticket->update(['status' => 'Proses']);
            }

            $pesan = 'Progres berhasil disimpan.';
        }

        // ------------------------------------------------------------------
        // Redirect kembali ke form progres tiket
        // ------------------------------------------------------------------
        return redirect()
            ->route('aplikasi.tickets.progress.form', $ticket->id)
            ->with('success', $pesan);
    }
}
