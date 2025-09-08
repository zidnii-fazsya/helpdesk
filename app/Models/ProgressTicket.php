<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressTicket extends Model
{
    protected $table = 'progress_tickets';

    protected $fillable = [
        'ticket_id',
        'narasi',
        'waktu_progres',
        'admin_aplikasi_id',
    ];

    protected $casts = [
        'waktu_progres' => 'datetime',
    ];

    /**
     * Relasi ke Ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    /**
     * Relasi ke AdminAplikasi yang menambahkan progres.
     */
    public function adminAplikasi(): BelongsTo
    {
        return $this->belongsTo(AdminAplikasi::class, 'admin_aplikasi_id', 'id');
    }

    /**
     * Relasi ke User (opsional, jika progress juga bisa dibuat oleh user/teknisi).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Accessor format waktu progres ke format jam:menit.
     */
    public function getWaktuProgresFormattedAttribute(): string
    {
        return $this->waktu_progres
            ? $this->waktu_progres->format('H:i')
            : '-';
    }

    /**
     * Accessor format tanggal lengkap.
     */
    public function getTanggalLengkapAttribute(): string
    {
        return $this->waktu_progres
            ? $this->waktu_progres->translatedFormat('l, d M Y H:i')
            : '-';
    }

    /**
     * Scope untuk mengambil progres berdasarkan ticket tertentu.
     */
    public function scopeByTicket($query, $ticketId)
    {
        return $query->where('ticket_id', $ticketId)
                     ->orderBy('waktu_progres', 'asc');
    }

    /**
     * Tambahkan progres baru untuk tiket secara langsung.
     */
    public static function addProgress($ticketId, $narasi, $adminAplikasiId = null)
    {
        return self::create([
            'ticket_id'         => $ticketId,
            'narasi'            => $narasi,
            'waktu_progres'     => now(),
            'admin_aplikasi_id' => $adminAplikasiId,
        ]);
    }
}
