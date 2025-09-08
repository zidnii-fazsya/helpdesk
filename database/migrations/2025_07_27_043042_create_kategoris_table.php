<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Buat tabel 'kategoris' jika belum ada
        if (!Schema::hasTable('kategoris')) {
            Schema::create('kategoris', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori')->unique()->comment('Nama kategori tiket, misal: Perangkat Keras / Perangkat Lunak');
                $table->timestamps();
            });
        }

        // Buat pivot table kategori <-> tiket jika belum ada
        if (!Schema::hasTable('kategori_ticket')) {
            Schema::create('kategori_ticket', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')
                      ->constrained('tickets')
                      ->onDelete('cascade');
                $table->foreignId('kategori_id')
                      ->constrained('kategoris')
                      ->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Kembalikan migrasi.
     */
    public function down(): void
    {
        // Drop pivot table dulu
        Schema::dropIfExists('kategori_ticket');

        // Drop tabel 'kategoris'
        Schema::dropIfExists('kategoris');
    }
};
