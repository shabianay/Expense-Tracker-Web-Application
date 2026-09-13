<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::firstOrCreate(['email' => 'default@example.com'], ['name' => 'Default User', 'password' => bcrypt('password')]);
    }

    public function test_index_renders(): void
    {
        $this->get('/categories')->assertStatus(200);
    }

    public function test_create_form_renders(): void
    {
        $this->get('/categories/create')->assertStatus(200);
    }

    public function test_store_category(): void
    {
        $this->post('/categories', [
            'name' => 'Entertainment',
            'type' => 'expense',
            'icon' => '🎬',
            'color' => '#FF0000',
        ])->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Entertainment', 'user_id' => $this->user->id]);
    }

    public function test_update_category(): void
    {
        $category = Category::create(['user_id' => $this->user->id, 'name' => 'Test', 'type' => 'expense', 'icon' => '🍔', 'color' => '#FF0000', 'sort_order' => 0]);

        $this->put("/categories/{$category->id}", [
            'name' => 'Updated',
            'type' => 'expense',
            'icon' => '🍔',
            'color' => '#00FF00',
        ])->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated']);
    }

    public function test_delete_category_reassigns_transactions(): void
    {
        $category = Category::create(['user_id' => $this->user->id, 'name' => 'ToDelete', 'type' => 'expense', 'icon' => '🍔', 'color' => '#FF0000', 'sort_order' => 0]);
        $transaction = Transaction::create(['user_id' => $this->user->id, 'category_id' => $category->id, 'type' => 'expense', 'amount' => 1000, 'transaction_date' => now()]);

        $this->delete("/categories/{$category->id}")->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $transaction->refresh();
        $uncategorized = Category::where('user_id', $this->user->id)->where('name', 'Uncategorized')->first();
        $this->assertNotNull($uncategorized);
        $this->assertEquals($uncategorized->id, $transaction->category_id);
    }

    public function test_reorder_categories(): void
    {
        $cat1 = Category::create(['user_id' => $this->user->id, 'name' => 'A', 'type' => 'expense', 'icon' => '🍔', 'color' => '#FF0000', 'sort_order' => 0]);
        $cat2 = Category::create(['user_id' => $this->user->id, 'name' => 'B', 'type' => 'expense', 'icon' => '🛒', 'color' => '#00FF00', 'sort_order' => 1]);

        $this->postJson('/categories/reorder', ['ids' => [$cat2->id, $cat1->id]])
            ->assertJson(['success' => true]);

        $this->assertEquals(2, $cat1->fresh()->sort_order);
        $this->assertEquals(1, $cat2->fresh()->sort_order);
    }
}
