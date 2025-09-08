<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_subkategori', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke tabel tickets
            $table->foreignId('ticket_id')
                  ->constrained('tickets')
                  ->onDelete('cascade');

            // Foreign key ke tabel sub_kategoris
            $table->foreignId('subkategori_id')
                  ->constrained('sub_kategoris')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_subkategori');
    }
};
