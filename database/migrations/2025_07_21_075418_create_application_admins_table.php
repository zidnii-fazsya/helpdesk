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
        // Hindari error jika tabel sudah ada
        if (!Schema::hasTable('application_admins')) {
            Schema::create('application_admins', function (Blueprint $table) {
                $table->id();

                // Pastikan foreign key menuju ke tabel 'applications'
                $table->foreignId('application_id')
                      ->constrained('applications')
                      ->onDelete('cascade');

                // Pastikan foreign key menuju ke tabel 'users'
                $table->foreignId('user_id')
                      ->constrained('users')
                      ->onDelete('cascade');

                $table->enum('role', ['admin_aplikasi', 'admin_helpdesk']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_admins');
    }
};
