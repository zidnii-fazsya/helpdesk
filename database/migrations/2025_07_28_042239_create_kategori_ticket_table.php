<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat pivot table kategori_ticket.
     */
    public function up(): void
    {
        // Pivot table untuk relasi many-to-many antara tickets dan kategoris
        if (!Schema::hasTable('kategori_ticket')) {
            Schema::create('kategori_ticket', function (Blueprint $table) {
                $table->id();

                // Foreign key ke tickets
                $table->foreignId('ticket_id')
                      ->constrained('tickets')
                      ->onDelete('cascade')
                      ->comment('Ticket terkait');

                // Foreign key ke kategoris
                $table->foreignId('kategori_id')
                      ->constrained('kategoris')
                      ->onDelete('cascade')
                      ->comment('Kategori terkait');

                $table->timestamps();
            });
        }
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        // Drop tabel pivot jika ada
        Schema::dropIfExists('kategori_ticket');
    }
};
