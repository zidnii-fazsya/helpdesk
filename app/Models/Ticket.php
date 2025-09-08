<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Ticket
 *
 * @property int $id
 * @property string $ticket_number
 * @property string $tanggal
 * @property string $reporter_name
 * @property string $jabatan
 * @property string $ruangan
 * @property string $satuan_kerja
 * @property string $kategori
 * @property string $keluhan
 * @property string $status
 * @property string $prioritas
 * @property string $keterangan_prioritas
 * @property int|null $aplikasi_id
 */
class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'ticket_number',
        'tanggal',
        'reporter_name',
        'jabatan',
        'ruangan',
        'satuan_kerja',
        'kategori',
        'keluhan',
        'status',
        'teknisi_nama',
        'teknisi_nip',
        'teknisi_kategori',
        'prioritas',
        'waktu_respon',
        'sla',
        'eskalasi',
        'keterangan_prioritas',
        'diambil_oleh',
        'aplikasi_id',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'waktu_respon' => 'integer',
        'sla'          => 'integer',
    ];

    protected $appends = ['keterangan_prioritas_formatted'];

    public const STATUS_MASUK   = 'Masuk';
    public const STATUS_PROSES  = 'Proses';
    public const STATUS_SELESAI = 'Selesai';

    /**
     * Relasi Many-to-Many ke Kategori
     */
    public function kategoriTickets(): BelongsToMany
    {
        return $this->belongsToMany(Kategori::class, 'kategori_ticket', 'ticket_id', 'kategori_id')
                    ->withTimestamps();
    }

    /**
     * Relasi Many-to-Many ke SubKategori
     */
    public function subkategoris(): BelongsToMany
    {
        return $this->belongsToMany(SubKategori::class, 'ticket_subkategori', 'ticket_id', 'subkategori_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Aplikasi (One-to-One)
     */
    public function aplikasi(): BelongsTo
    {
        return $this->belongsTo(Aplikasi::class, 'aplikasi_id');
    }

    /**
     * Relasi Many-to-Many ke Aplikasi
     */
    public function aplikasis(): BelongsToMany
    {
        return $this->belongsToMany(Aplikasi::class, 'ticket_aplikasi', 'ticket_id', 'aplikasi_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Progress Ticket
     */
    public function progresses(): HasMany
    {
        return $this->hasMany(ProgressTicket::class, 'ticket_id', 'id')
                    ->orderBy('waktu_progres', 'asc');
    }

    public function latestProgress(): HasOne
    {
        return $this->hasOne(ProgressTicket::class, 'ticket_id', 'id')->latestOfMany();
    }

    public function progress(): HasOne
    {
        return $this->latestProgress();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ProgressTicket::class, 'ticket_id', 'id');
    }

    /**
     * Getter untuk teknisi
     */
    public function getTeknisiAttribute(): ?object
    {
        if (!$this->teknisi_nama && !$this->teknisi_nip) {
            return null;
        }

        return (object) [
            'name'              => $this->teknisi_nama,
            'nip'               => $this->teknisi_nip,
            'kategori_aplikasi' => $this->teknisi_kategori,
        ];
    }

    public function getSatuanKerjaFormattedAttribute(): string
    {
        return $this->satuan_kerja ?: '-';
    }

    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isEditable(): bool
    {
        return $this->status === self::STATUS_MASUK;
    }

    /**
     * Tentukan prioritas otomatis jika tidak diisi manual.
     */
    public function tentukanPrioritasOtomatis(): string
    {
        if (!empty($this->prioritas)) {
            return strtolower($this->prioritas);
        }

        if ($this->sla !== null) {
            if ($this->sla <= 60) {
                return 'tinggi';
            } elseif ($this->sla <= 240) {
                return 'sedang';
            }
        }

        return 'rendah';
    }

    public function getKeteranganPrioritasFormattedAttribute(): string
    {
        if (!empty($this->keterangan_prioritas)) {
            return $this->keterangan_prioritas;
        }

        return match (strtolower($this->tentukanPrioritasOtomatis())) {
            'tinggi' => 'Prioritas Tinggi — perlu penanganan segera untuk mencegah gangguan layanan.',
            'sedang' => 'Prioritas Sedang — perlu ditangani dalam waktu wajar sesuai SLA.',
            default  => 'Prioritas Rendah — dapat ditangani setelah tiket prioritas lebih tinggi selesai.',
        };
    }

    /**
     * Helper untuk mengambil subkategori berdasarkan kategori
     */
    public function getSubkategorisByKategori(array $kategoriIds)
    {
        return SubKategori::whereIn('kategori_id', $kategoriIds)->get();
    }

    public function getAplikasisByKategori(array $kategoriIds)
    {
        return Aplikasi::whereIn('kategori_id', $kategoriIds)->get();
    }
}
