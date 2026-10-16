<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id')->nullable();
            $table->integer('booking_id')->nullable();
            $table->string('nama_user', 100)->nullable();
            $table->string('status_lama', 50)->nullable();
            $table->string('status_baru', 50)->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamp('waktu')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_pembayaran');
    }
};