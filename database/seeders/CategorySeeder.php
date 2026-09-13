<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            static::seedForUser($user);
        }
    }

    public static function seedForUser(User $user): void
    {
        $expenseCategories = [
            ['name' => 'Food', 'icon' => '🍔', 'color' => '#FF6B6B'],
            ['name' => 'Clothing', 'icon' => '👕', 'color' => '#4ECDC4'],
            ['name' => 'Pets', 'icon' => '🐾', 'color' => '#FFE66D'],
            ['name' => 'Shopping', 'icon' => '🛒', 'color' => '#95E1D3'],
            ['name' => 'Coffee', 'icon' => '☕', 'color' => '#F38181'],
            ['name' => 'Travel', 'icon' => '✈️', 'color' => '#AA96DA'],
            ['name' => 'Sports', 'icon' => '🎯', 'color' => '#FCBAD3'],
            ['name' => 'Gaming', 'icon' => '🎮', 'color' => '#A8D8EA'],
            ['name' => 'Gifts', 'icon' => '🎁', 'color' => '#FF9A76'],
            ['name' => 'Fruit', 'icon' => '🍎', 'color' => '#6BCB77'],
            ['name' => 'Health', 'icon' => '🏥', 'color' => '#FF6B6B'],
            ['name' => 'Transport', 'icon' => '🚗', 'color' => '#4D96FF'],
        ];

        $incomeCategories = [
            ['name' => 'Salary', 'icon' => '💰', 'color' => '#6BCB77'],
            ['name' => 'Freelance', 'icon' => '💻', 'color' => '#4D96FF'],
            ['name' => 'Investment', 'icon' => '📊', 'color' => '#AA96DA'],
            ['name' => 'Gifts Received', 'icon' => '🎁', 'color' => '#FF9A76'],
            ['name' => 'Other Income', 'icon' => '💰', 'color' => '#95E1D3'],
        ];

        $sortOrder = 0;
        foreach ($expenseCategories as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name' => $cat['name'],
                'type' => 'expense',
                'icon' => $cat['icon'],
                'color' => $cat['color'],
                'sort_order' => $sortOrder++,
            ]);
        }

        $sortOrder = 0;
        foreach ($incomeCategories as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name' => $cat['name'],
                'type' => 'income',
                'icon' => $cat['icon'],
                'color' => $cat['color'],
                'sort_order' => $sortOrder++,
            ]);
        }
    }
}
