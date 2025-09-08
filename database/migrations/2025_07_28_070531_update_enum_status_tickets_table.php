<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnumStatusTicketsTable extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY status ENUM('Masuk', 'Proses', 'Selesai') NOT NULL DEFAULT 'Masuk'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY status ENUM('Pending', 'Proses', 'Selesai') NOT NULL DEFAULT 'Pending'");
    }
}
