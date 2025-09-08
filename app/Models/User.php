<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang bisa diisi massal.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'no_hp',
        'aplikasi_id',       // Foreign key ke tabel aplikasis (jika diperlukan)
        'kategori_aplikasi', // Jika kategori aplikasi disimpan langsung di tabel users
    ];

    /**
     * Atribut yang disembunyikan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast atribut otomatis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel >= 10
    ];

    // =======================
    // === RELASI DATABASE ===
    // =======================

    /**
     * Relasi: User ke aplikasi utama (jika pakai field aplikasi_id).
     */
    public function aplikasi(): BelongsTo
    {
        return $this->belongsTo(Aplikasi::class, 'aplikasi_id');
    }

    /**
     * Relasi many-to-many via pivot table application_admins.
     */
    public function aplikasis(): BelongsToMany
    {
        return $this->belongsToMany(Aplikasi::class, 'application_admins')
                    ->withPivot('role') // contoh: admin_aplikasi, admin_helpdesk
                    ->withTimestamps();
    }

    /**
     * Aplikasi yang dikelola sebagai admin aplikasi.
     */
    public function aplikasiYangDikelola(): BelongsToMany
    {
        return $this->aplikasis()->wherePivot('role', 'admin_aplikasi');
    }

    /**
     * Aplikasi yang ditangani sebagai admin helpdesk.
     */
    public function aplikasiYangDitangani(): BelongsToMany
    {
        return $this->aplikasis()->wherePivot('role', 'admin_helpdesk');
    }

    /**
     * Relasi ke progress tiket (jika user = teknisi).
     */
    public function progressTickets(): HasMany
    {
        return $this->hasMany(ProgressTicket::class, 'user_id');
    }

    // =======================
    // === CEK ROLE METHOD ===
    // =======================

    public function isMasterHelpdesk(): bool
    {
        return $this->role === 'master_helpdesk';
    }

    public function isAdminHelpdesk(): bool
    {
        return $this->role === 'admin helpdesk';
    }

    public function isAdminAplikasi(): bool
    {
        return $this->role === 'admin aplikasi';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }
}
