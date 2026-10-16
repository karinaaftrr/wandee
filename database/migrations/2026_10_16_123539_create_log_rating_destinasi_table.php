<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_rating_destinasi', function (Blueprint $table) {
            $table->id();
            $table->integer('destination_id')->nullable();
            $table->string('judul_destinasi')->nullable();
            $table->decimal('rating_lama', 2, 1)->nullable();
            $table->decimal('rating_baru', 2, 1)->nullable();
            $table->integer('total_review')->nullable();
            $table->string('keterangan', 100)->nullable();
            $table->timestamp('waktu')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_rating_destinasi');
    }
};