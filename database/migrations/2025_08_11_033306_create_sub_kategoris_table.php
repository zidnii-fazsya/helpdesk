<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel sub_kategoris.
     */
    public function up(): void
    {
        // Buat tabel 'sub_kategoris' jika belum ada
        if (!Schema::hasTable('sub_kategoris')) {
            Schema::create('sub_kategoris', function (Blueprint $table) {
                $table->id();
                
                $table->foreignId('kategori_id')
                      ->constrained('kategoris') // Relasi ke tabel 'kategoris'
                      ->onDelete('cascade')
                      ->comment('Kategori induk dari subkategori');

                $table->string('nama_subkategori')->comment('Nama subkategori, misal: Printer, Software Akuntansi');

                $table->timestamps();
            });
        }

        // Pivot table ticket <-> subkategori (many-to-many)
        if (!Schema::hasTable('ticket_subkategori')) {
            Schema::create('ticket_subkategori', function (Blueprint $table) {
                $table->id();

                $table->foreignId('ticket_id')
                      ->constrained('tickets')
                      ->onDelete('cascade');

                $table->foreignId('subkategori_id')
                      ->constrained('sub_kategoris')
                      ->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Hapus tabel sub_kategoris beserta pivot table-nya.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_subkategori'); // Pivot table di-drop dulu
        Schema::dropIfExists('sub_kategoris');
    }
};
