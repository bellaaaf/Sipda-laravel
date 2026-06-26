<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bencana_id')->nullable()->constrained('data_bencana')->nullOnDelete();
            $table->string('judul');
            $table->text('isi');
            $table->string('foto')->nullable();
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
