<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Foreign key ke tabel aplikasis
            if (!Schema::hasColumn('tickets', 'aplikasi_id')) {
                $table->foreignId('aplikasi_id')
                    ->nullable()
                    ->constrained('aplikasis')
                    ->onDelete('set null')
                    ->after('id');
            }

            // Foreign key ke tabel sub_kategoris
            if (!Schema::hasColumn('tickets', 'subkategori_id')) {
                $table->foreignId('subkategori_id')
                    ->nullable()
                    ->constrained('sub_kategoris') // <-- pastikan nama tabel benar
                    ->onDelete('set null')
                    ->after('aplikasi_id');
            }

            // Kolom tambahan untuk menyimpan nama admin yang mengambil tiket
            if (!Schema::hasColumn('tickets', 'diambil_oleh')) {
                $table->string('diambil_oleh')
                    ->nullable()
                    ->after('keterangan_prioritas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop foreign key terlebih dahulu
            if (Schema::hasColumn('tickets', 'aplikasi_id')) {
                $table->dropForeign(['aplikasi_id']);
            }

            if (Schema::hasColumn('tickets', 'subkategori_id')) {
                $table->dropForeign(['subkategori_id']);
            }

            // Drop kolom
            $table->dropColumn(['aplikasi_id', 'subkategori_id', 'diambil_oleh']);
        });
    }
};
