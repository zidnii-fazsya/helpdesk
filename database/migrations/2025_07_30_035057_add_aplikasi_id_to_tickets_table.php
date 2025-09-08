<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tickets', 'aplikasi_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                // Kolom aplikasi_id boleh kosong
                $table->unsignedBigInteger('aplikasi_id')->nullable()->after('id');

                // Tambahkan foreign key dengan ON DELETE SET NULL
                $table->foreign('aplikasi_id')
                    ->references('id')
                    ->on('aplikasis')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tickets', 'aplikasi_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropForeign(['aplikasi_id']);
                $table->dropColumn('aplikasi_id');
            });
        }
    }
};
