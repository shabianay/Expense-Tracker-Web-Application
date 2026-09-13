<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Ensure a default user exists for seeding categories
        User::firstOrCreate(
            ['email' => 'default@example.com'],
            ['name' => 'Default User', 'password' => bcrypt('password')]
        );

        $this->call(CategorySeeder::class);
    }
}
