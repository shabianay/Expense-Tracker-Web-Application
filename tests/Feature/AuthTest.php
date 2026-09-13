<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_users_can_register_and_get_default_categories(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertGreaterThan(0, Category::where('user_id', $user->id)->count());
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user1@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_cannot_see_other_users_data(): void
    {
        $user1 = User::create(['name' => 'User One', 'email' => 'u1@example.com', 'password' => bcrypt('password')]);
        $user2 = User::create(['name' => 'User Two', 'email' => 'u2@example.com', 'password' => bcrypt('password')]);

        $cat1 = Category::create(['user_id' => $user1->id, 'name' => 'Secret User1 Category', 'type' => 'expense']);
        $cat2 = Category::create(['user_id' => $user2->id, 'name' => 'Secret User2 Category', 'type' => 'expense']);

        Transaction::create(['user_id' => $user1->id, 'category_id' => $cat1->id, 'type' => 'expense', 'amount' => 999000, 'transaction_date' => now()]);

        $response = $this->actingAs($user2)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertDontSee('Secret User1 Category');
        $response->assertDontSee('999.000');
    }
}
