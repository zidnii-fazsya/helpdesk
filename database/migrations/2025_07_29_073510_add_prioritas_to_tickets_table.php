<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // ⚠️ Tidak perlu tambah 'prioritas' lagi karena sudah ada di migration utama

            if (!Schema::hasColumn('tickets', 'waktu_respon')) {
                $table->integer('waktu_respon')
                    ->nullable()
                    ->after('prioritas')
                    ->comment('Waktu respon dalam menit atau jam');
            }

            if (!Schema::hasColumn('tickets', 'sla')) {
                $table->integer('sla')
                    ->nullable()
                    ->after('waktu_respon')
                    ->comment('SLA tiket dalam jam atau hari');
            }

            if (!Schema::hasColumn('tickets', 'eskalasi')) {
                $table->string('eskalasi')
                    ->nullable()
                    ->after('sla')
                    ->comment('Kolom eskalasi tiket');
            }

            if (!Schema::hasColumn('tickets', 'keterangan_prioritas')) {
                $table->text('keterangan_prioritas')
                    ->nullable()
                    ->after('eskalasi')
                    ->comment('Keterangan tambahan terkait prioritas tiket');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $columns = [
                'waktu_respon',
                'sla',
                'eskalasi',
                'keterangan_prioritas'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tickets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
