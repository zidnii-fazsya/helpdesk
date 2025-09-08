<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAplikasi extends Model
{
    use HasFactory;

    protected $table = 'admin_aplikasis';

    protected $fillable = [
        'name',        // nama admin aplikasi
        'nip',         // NIP admin
        'handled_app', // aplikasi yang ditangani
    ];

    /**
     * Relasi ke ProgressTicket.
     * Seorang admin aplikasi bisa menangani banyak progress tiket.
     */
    public function progressTickets()
    {
        return $this->hasMany(ProgressTicket::class, 'admin_aplikasi_id', 'id');
    }
}
