<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom kategori ke tabel tickets jika belum ada.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'kategori')) {
                $table->enum('kategori', ['Perbaikan Software', 'Perbaikan Hardware'])
                      ->default('Perbaikan Software')
                      ->after('ruangan');
            }
        });
    }

    /**
     * Menghapus kolom kategori dari tabel tickets jika ada.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};
