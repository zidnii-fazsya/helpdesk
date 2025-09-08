<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Aplikasi
 *
 * @property int $id
 * @property string $nama_aplikasi
 * @property int $kategori_id
 */
class Aplikasi extends Model
{
    use HasFactory;

    protected $table = 'aplikasis';

    protected $fillable = [
        'nama_aplikasi',
        'kategori_id',
    ];

    /**
     * Relasi ke kategori (Many-to-One)
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi Many-to-Many ke Ticket
     */
    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_aplikasi', 'aplikasi_id', 'ticket_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke user (One-to-Many)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'aplikasi_id', 'id');
    }

    /**
     * Relasi admin aplikasi dan helpdesk (Many-to-Many)
     */
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'application_admins', 'aplikasi_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function adminAplikasi(): BelongsToMany
    {
        return $this->admins()->wherePivot('role', 'admin_aplikasi');
    }

    public function adminHelpdesk(): BelongsToMany
    {
        return $this->admins()->wherePivot('role', 'admin_helpdesk');
    }
}
