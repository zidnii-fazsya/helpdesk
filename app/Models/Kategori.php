<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Kategori
 *
 * @property int $id
 * @property string $nama_kategori
 */
class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    /**
     * Relasi Many-to-Many ke Ticket
     */
    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'kategori_ticket', 'kategori_id', 'ticket_id')
                    ->withTimestamps();
    }

    /**
     * Relasi One-to-Many ke SubKategori
     */
    public function subKategoris(): HasMany
    {
        return $this->hasMany(SubKategori::class, 'kategori_id', 'id');
    }

    /**
     * Relasi One-to-Many ke Aplikasi
     */
    public function aplikasis(): HasMany
    {
        return $this->hasMany(Aplikasi::class, 'kategori_id', 'id');
    }

    /**
     * Scope filter berdasarkan nama kategori
     */
    public function scopeFilterByName($query, ?string $nama)
    {
        if ($nama) {
            return $query->where('nama_kategori', 'LIKE', "%$nama%");
        }
        return $query;
    }
}
