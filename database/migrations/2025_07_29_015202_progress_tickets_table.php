<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('progress_tickets')) {
            Schema::create('progress_tickets', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('ticket_id')->index();
                $table->unsignedBigInteger('admin_aplikasi_id')->index();

                $table->text('narasi');
                $table->time('waktu_progres')->nullable();

                $table->timestamps();

                $table->foreign('ticket_id', 'fk_progress_ticket_id')
                    ->references('id')->on('tickets')->onDelete('cascade');

                $table->foreign('admin_aplikasi_id', 'fk_progress_admin_id')
                    ->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('progress_tickets')) {
            Schema::table('progress_tickets', function (Blueprint $table) {
                $table->dropForeign('fk_progress_ticket_id');
                $table->dropForeign('fk_progress_admin_id');
            });

            Schema::dropIfExists('progress_tickets');
        }
    }
};
