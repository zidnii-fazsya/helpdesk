<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel tickets utama (hanya tabel tickets, tanpa pivot apa pun)
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();

                // FK ke aplikasis: BOLEH KOSONG (nullable) + ON DELETE SET NULL
                // Letakkan setelah id agar mudah dibaca
                $table->foreignId('aplikasi_id')
                      ->nullable()
                      ->constrained('aplikasis')
                      ->nullOnDelete();

                $table->string('ticket_number')->unique()->comment('Nomor unik tiket');
                $table->date('tanggal')->comment('Tanggal tiket dibuat');

                $table->string('reporter_name')->comment('Nama pelapor');
                $table->string('jabatan')->comment('Jabatan pelapor');
                $table->string('ruangan')->comment('Ruangan pelapor');
                $table->string('satuan_kerja')->nullable()->comment('Satuan kerja pelapor');

                $table->text('keluhan')->comment('Detail keluhan');

                $table->enum('status', ['Masuk', 'Proses', 'Selesai'])
                      ->default('Masuk')
                      ->comment('Status tiket');

                $table->enum('prioritas', ['tinggi', 'sedang', 'rendah'])
                      ->default('rendah')
                      ->comment('Prioritas tiket');

                $table->integer('waktu_respon')->nullable()->comment('Waktu respon dalam menit');
                $table->integer('sla')->nullable()->comment('Service Level Agreement dalam menit');
                $table->string('eskalasi')->nullable()->comment('Pihak atau divisi eskalasi');
                $table->text('keterangan_prioritas')->nullable()->comment('Keterangan tambahan terkait prioritas');

                $table->string('diambil_oleh')->nullable()->comment('Nama admin yang mengambil tiket');

                $table->string('teknisi_nama')->nullable()->comment('Nama teknisi penangan tiket');
                $table->string('teknisi_nip')->nullable()->comment('NIP teknisi');
                $table->string('teknisi_kategori')->nullable()->comment('Kategori teknisi');

                // Kolom ini memang pernah ada di project kamu
                $table->string('kategori_aplikasi')->nullable()->comment('Kategori aplikasi yang terkait tiket');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
