<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_stores_a_hashed_password(): void
    {
        $this->postJson('/api/dmusic/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ])->assertCreated();

        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->postJson('/api/dmusic/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized();
    }
}
