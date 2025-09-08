<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel pivot hanya jika belum ada
        if (!Schema::hasTable('ticket_aplikasi')) {
            Schema::create('ticket_aplikasi', function (Blueprint $table) {
                $table->id();

                // FK ke tickets
                $table->foreignId('ticket_id')
                      ->constrained('tickets')
                      ->cascadeOnDelete();

                // FK ke aplikasis
                $table->foreignId('aplikasi_id')
                      ->constrained('aplikasis')
                      ->cascadeOnDelete();

                $table->timestamps();

                // Cegah duplikasi pasangan ticket-aplikasi
                $table->unique(['ticket_id', 'aplikasi_id'], 'uniq_ticket_aplikasi');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_aplikasi');
    }
};
