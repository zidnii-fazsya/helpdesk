<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'teknisi_nama')) {
                $table->string('teknisi_nama')->nullable();
            }
            if (!Schema::hasColumn('tickets', 'teknisi_nip')) {
                $table->string('teknisi_nip')->nullable();
            }
            if (!Schema::hasColumn('tickets', 'teknisi_kategori')) {
                $table->string('teknisi_kategori')->nullable(); // contoh: Software/Hardware
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'teknisi_nama')) {
                $table->dropColumn('teknisi_nama');
            }
            if (Schema::hasColumn('tickets', 'teknisi_nip')) {
                $table->dropColumn('teknisi_nip');
            }
            if (Schema::hasColumn('tickets', 'teknisi_kategori')) {
                $table->dropColumn('teknisi_kategori');
            }
        });
    }
};
