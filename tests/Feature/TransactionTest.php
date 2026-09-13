<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
        $this->category = Category::create(['user_id' => $this->user->id, 'name' => 'Food', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#FF0000', 'sort_order' => 0]);
    }

    public function test_create_form_renders(): void
    {
        $this->get('/transactions/create')->assertStatus(200);
    }

    public function test_store_transaction(): void
    {
        $this->post('/transactions', [
            'amount' => 25000,
            'type' => 'expense',
            'category_id' => $this->category->id,
            'note' => 'Lunch',
            'date' => '2026-09-12 12:00:00',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('transactions', ['user_id' => $this->user->id, 'amount' => 25000, 'note' => 'Lunch']);
    }

    public function test_edit_form_renders(): void
    {
        $transaction = Transaction::create(['user_id' => $this->user->id, 'category_id' => $this->category->id, 'type' => 'expense', 'amount' => 10000, 'transaction_date' => now()]);

        $this->get("/transactions/{$transaction->id}/edit")->assertStatus(200);
    }

    public function test_update_transaction(): void
    {
        $transaction = Transaction::create(['user_id' => $this->user->id, 'category_id' => $this->category->id, 'type' => 'expense', 'amount' => 10000, 'transaction_date' => now()]);

        $this->put("/transactions/{$transaction->id}", [
            'amount' => 15000,
            'type' => 'expense',
            'category_id' => $this->category->id,
            'note' => 'Updated',
            'date' => '2026-09-12 12:00:00',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'amount' => 15000]);
    }

    public function test_delete_transaction(): void
    {
        $transaction = Transaction::create(['user_id' => $this->user->id, 'category_id' => $this->category->id, 'type' => 'expense', 'amount' => 10000, 'transaction_date' => now()]);

        $this->delete("/transactions/{$transaction->id}")->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
