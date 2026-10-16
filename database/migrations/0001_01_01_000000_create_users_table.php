
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('role', 20)->default('user');
            $table->string('photo')->nullable();
            $table->string('oauth_provider', 50)->nullable();
            $table->string('oauth_uid')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
