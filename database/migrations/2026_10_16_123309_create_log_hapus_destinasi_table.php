<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_hapus_destinasi', function (Blueprint $table) {
            $table->id();
            $table->integer('dest_id')->nullable();
            $table->string('judul')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('kategori', 100)->nullable();
            $table->string('harga', 100)->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->timestamp('dihapus_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_hapus_destinasi');
    }
};