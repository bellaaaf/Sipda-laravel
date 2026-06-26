<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_bencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_id')->nullable()->constrained('jenis_bencana')->nullOnDelete();
            $table->string('lokasi');
            $table->enum('tingkat_status', ['Darurat', 'Siaga', 'Waspada', 'Aman'])->default('Waspada');
            $table->date('tanggal_kejadian');
            $table->text('deskripsi')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_bencana');
    }
};
