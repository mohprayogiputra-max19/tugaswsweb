<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SanctumTokenApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_credentials_issue_a_bearer_token_for_protected_user_endpoint(): void
    {
        $user = User::factory()->create([
            'email' => 'kasir@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $token = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'correct-password',
        ])
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->json('access_token');

        $this->assertIsString($token);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'auth_token',
        ]);

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$token,
        ])
            ->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('email', $user->email)
            ->assertJsonPath('name', $user->name)
            ->assertJsonPath('is_admin', 0)
            ->assertJsonPath('role', 'kasir')
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'is_admin',
                'email_verified_at',
                'role',
                'created_at',
                'updated_at',
            ])
            ->assertJsonMissingPath('password');
    }

    public function test_api_user_reports_admin_flag_for_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('test')->plainTextToken;

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$token,
        ])
            ->assertOk()
            ->assertJsonPath('is_admin', 1)
            ->assertJsonPath('role', 'admin');
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'kasir@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $this->postJson('/api/login', [
            'email' => 'kasir@example.test',
            'password' => 'wrong-password',
        ])
            ->assertUnauthorized()
            ->assertJson(['message' => 'Invalid credentials']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_requires_email_and_password(): void
    {
        $this->postJson('/api/login', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_protected_api_user_endpoint_rejects_guests_and_invalid_tokens(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer invalid-token',
        ])->assertUnauthorized();
    }

    public function test_logout_revokes_only_the_current_token(): void
    {
        $user = User::factory()->create();
        $currentToken = $user->createToken('current')->plainTextToken;
        $otherToken = $user->createToken('other')->plainTextToken;

        $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer '.$currentToken,
        ])
            ->assertOk()
            ->assertJson(['message' => 'Logged out']);

        $this->assertDatabaseCount('personal_access_tokens', 1);

        Auth::forgetGuards();

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$currentToken,
        ])->assertUnauthorized();

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$otherToken,
        ])->assertOk();
    }

    public function test_logout_all_revokes_every_token_for_the_current_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('current')->plainTextToken;
        $user->createToken('other');

        $this->postJson('/api/logout-all', [], [
            'Authorization' => 'Bearer '.$token,
        ])
            ->assertOk()
            ->assertJson(['message' => 'Logged out from all devices']);

        $this->assertDatabaseCount('personal_access_tokens', 0);

        Auth::forgetGuards();

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer '.$token,
        ])->assertUnauthorized();
    }
}
