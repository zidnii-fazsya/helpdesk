<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'kategori_aplikasi')) {
                $table->string('kategori_aplikasi')->nullable()->after('teknisi_kategori');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'kategori_aplikasi')) {
                $table->dropColumn('kategori_aplikasi');
            }
        });
    }
};
