
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Columns already defined in create_users_table migration.
        // This migration is kept as a no-op to avoid duplicate column errors.
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'photo',
                'oauth_provider',
                'oauth_uid',
            ]);
        });
    }
};
