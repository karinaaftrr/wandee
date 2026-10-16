<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->cascadeOnDelete();
            $table->foreignId('destination_id')
                ->constrained('destinations')->cascadeOnDelete();
            $table->integer('rating')->default(5);
            $table->text('review')->nullable();
            $table->string('review_image')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};