<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_masyarakat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pelapor');
            $table->string('email_pelapor');
            $table->string('telepon', 20)->nullable();
            $table->string('lokasi_kejadian');
            $table->string('jenis_bencana');
            $table->text('deskripsi');
            $table->enum('tingkat_keparahan', ['Ringan', 'Sedang', 'Berat', 'Sangat Berat'])->default('Sedang');
            $table->integer('korban_jiwa')->default(0);
            $table->integer('korban_luka')->default(0);
            $table->integer('rumah_rusak')->default(0);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai', 'hoaks', 'ditolak', 'ditinjau'])->default('pending');
            $table->text('catatan_petugas')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->date('tanggal_kejadian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_masyarakat');
    }
};
