<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_csv(): void
    {
        $user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
        $cat = Category::create(['user_id' => $user->id, 'name' => 'Food', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#FF0000', 'sort_order' => 0]);
        Transaction::create(['user_id' => $user->id, 'category_id' => $cat->id, 'type' => 'expense', 'amount' => 25000, 'note' => 'Lunch', 'transaction_date' => '2026-09-10 12:00:00']);

        $response = $this->actingAs($user)->get('/export?month=2026-09');
        $response->assertStatus(200)->assertHeader('content-type', 'text/csv; charset=utf-8');
        $response->assertDownload('transactions_2026-09.csv');
    }

    public function test_export_empty_month(): void
    {
        $user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
        $this->actingAs($user)->get('/export?month=2026-01')->assertStatus(200);
    }
}
