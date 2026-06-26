<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('update_bencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bencana_id')->constrained('data_bencana')->cascadeOnDelete();
            $table->enum('status', ['Darurat', 'Siaga', 'Waspada', 'Aman']);
            $table->text('deskripsi');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('update_bencana');
    }
};
