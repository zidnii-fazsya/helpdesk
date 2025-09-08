<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi: Membuat tabel `aplikasis`.
     */
    public function up(): void
    {
        // Buat tabel 'aplikasis' jika belum ada
        if (!Schema::hasTable('aplikasis')) {
            Schema::create('aplikasis', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->string('nama_aplikasi')
                      ->unique()
                      ->comment('Nama aplikasi, misal: Akuntansi, HRIS');

                $table->foreignId('kategori_id')
                      ->nullable()
                      ->constrained('kategoris')
                      ->nullOnDelete()
                      ->comment('Kategori induk aplikasi (opsional)');

                $table->timestamps();
            });
        }

        // Buat tabel pivot 'ticket_aplikasi' jika belum ada
        if (!Schema::hasTable('ticket_aplikasi')) {
            Schema::create('ticket_aplikasi', function (Blueprint $table) {
                $table->id();

                $table->foreignId('ticket_id')
                      ->constrained('tickets')
                      ->cascadeOnDelete();

                $table->foreignId('aplikasi_id')
                      ->constrained('aplikasis')
                      ->cascadeOnDelete();

                $table->timestamps();
            });
        }
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_aplikasi');
        Schema::dropIfExists('aplikasis');
    }
};
