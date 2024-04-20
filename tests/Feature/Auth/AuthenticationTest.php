<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $url = '/api/login';
    protected $acceptJsonHeader = ['Accept' => 'application/json'];

    public function test_login_with_nonexistent_user_should_return_not_found(): void
    {
        // Arrange
        $invalidPayload = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        // Act
        $response = $this->post($this->url, $invalidPayload, $this->acceptJsonHeader);

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJsonStructure(['success', 'message']);
    }

    public function test_users_can_authenticate(): void
    {
        // Arrange
        $user = User::factory()->create();
        $payload = ['email' => $user->email, 'password' => 'password'];

        // Act
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        // Arrange
        $user = User::factory()->create();
        $invalidPayload = ['email' => $user->email, 'password' => 'wrong-password'];

        // Act
        $response = $this->post($this->url, $invalidPayload, $this->acceptJsonHeader);

        // Assert
        $this->assertGuest();
    }

}
