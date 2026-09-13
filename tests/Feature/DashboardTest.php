<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders(): void
    {
        $user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
    }

    public function test_dashboard_shows_correct_month_data(): void
    {
        $user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
        $category = Category::create(['user_id' => $user->id, 'name' => 'Food', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#FF0000', 'sort_order' => 0]);
        Transaction::create(['user_id' => $user->id, 'category_id' => $category->id, 'type' => 'expense', 'amount' => 50000, 'transaction_date' => '2026-09-05 10:00:00']);

        $this->actingAs($user)->get('/dashboard?month=2026-09')->assertStatus(200)->assertSee('50');
    }
}
