<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DestinationSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'user@wandee.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'tiwimd.contact@gmail.com'],
            [
                'name' => 'Tiwi Mustika Dewi',
                'password' => Hash::make('tiwi123'),
                'role' => 'user',
            ]
        );
    }
}
