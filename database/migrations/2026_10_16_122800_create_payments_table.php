
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->nullOnDelete();

            $table->string('payment_method', 100)->nullable();
            $table->integer('payment_amount')->nullable();
            $table->integer('unique_code')->nullable();
            $table->string('payment_proof')->nullable();
            $table->string('payment_status', 20)->default('waiting');
            $table->timestamps();
            $table->text('rejection_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
