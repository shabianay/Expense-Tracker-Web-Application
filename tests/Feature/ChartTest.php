<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChartTest extends TestCase
{
    use RefreshDatabase;

    public function test_charts_page_renders(): void
    {
        $user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
        $this->actingAs($user)->get('/charts')->assertStatus(200);
    }

    public function test_charts_with_data(): void
    {
        $user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
        $cat = Category::create(['user_id' => $user->id, 'name' => 'Food', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#FF0000', 'sort_order' => 0]);
        Transaction::create(['user_id' => $user->id, 'category_id' => $cat->id, 'type' => 'expense', 'amount' => 50000, 'transaction_date' => now()]);

        $this->actingAs($user)->get('/charts?month='.now()->format('Y-m'))->assertStatus(200)->assertSee('Food');
    }
}
