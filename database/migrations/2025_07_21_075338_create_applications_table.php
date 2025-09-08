<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Cek dulu apakah tabel sudah ada
        if (!Schema::hasTable('application_admins')) {
            Schema::create('application_admins', function (Blueprint $table) {
                $table->id();

                // Relasi ke tabel 'applications'
                $table->foreignId('application_id')
                    ->constrained('applications')
                    ->onDelete('cascade');

                // Relasi ke tabel 'users'
                $table->foreignId('user_id')
                    ->constrained('users')
                    ->onDelete('cascade');

                $table->enum('role', ['admin_aplikasi', 'admin_helpdesk'])->default('admin_aplikasi');

                $table->timestamps();
            });
        }
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_admins');
    }
};
