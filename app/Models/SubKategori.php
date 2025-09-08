<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class SubKategori
 *
 * @property int $id
 * @property string $nama_subkategori
 * @property int $kategori_id
 */
class SubKategori extends Model
{
    use HasFactory;

    protected $table = 'sub_kategoris';

    protected $fillable = [
        'kategori_id',
        'nama_subkategori',
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
        return $this->belongsToMany(Ticket::class, 'ticket_subkategori', 'subkategori_id', 'ticket_id')
                    ->withTimestamps();
    }
}
