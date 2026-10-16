
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('location', 255);
            $table->string('category', 100);
            $table->string('image', 255);
            $table->string('trip_date', 255)->nullable();
            $table->string('price', 100)->nullable();
            $table->integer('quota')->default(0);
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->text('description')->nullable();
            $table->text('itinerary')->nullable();
            $table->text('facilities')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
