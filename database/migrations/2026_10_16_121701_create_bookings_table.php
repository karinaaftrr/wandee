
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('destination_id')
                ->nullable()
                ->constrained('destinations')
                ->nullOnDelete();

            $table->integer('total_people')->default(1);
            $table->integer('total_price')->nullable();

            $table->string('payment_status', 20)->default('pending');
            $table->string('trip_status', 20)->default('new');

            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
