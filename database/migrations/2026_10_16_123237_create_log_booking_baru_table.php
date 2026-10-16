<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_booking_baru', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('destination_id')->nullable();
            $table->integer('total_people')->nullable();
            $table->integer('total_price')->nullable();
            $table->string('keterangan', 100)->nullable();
            $table->timestamp('waktu')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_booking_baru');
    }
};