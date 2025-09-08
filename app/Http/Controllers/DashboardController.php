<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\View\View;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Middleware('auth')] // Hanya user login yang bisa lihat dashboard
class DashboardController extends Controller
{
    #[Get('/dashboard', name: 'dashboard')]
    public function index(): View
    {
        // Ambil hanya 10 tiket terbaru dengan relasi aplikasi dan subkategori
        $allTickets = Ticket::with(['aplikasi', 'subkategoris'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', [
            // Statistik tiket berdasarkan status
            'jumlah_tiket_masuk'   => Ticket::where('status', 'Masuk')->count(),
            'jumlah_tiket_proses'  => Ticket::where('status', 'Proses')->count(),
            'jumlah_tiket_selesai' => Ticket::where('status', 'Selesai')->count(),

            // Statistik tiket berdasarkan prioritas
            'jumlah_prioritas_rendah'  => Ticket::where('prioritas', 'rendah')->count(),
            'jumlah_prioritas_sedang'  => Ticket::where('prioritas', 'sedang')->count(),
            'jumlah_prioritas_tinggi'  => Ticket::where('prioritas', 'tinggi')->count(),

            // Data tiket terbaru
            'tickets' => $allTickets,
        ]);
    }
}
