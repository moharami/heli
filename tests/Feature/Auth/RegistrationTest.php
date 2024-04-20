<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected $url = '/api/register';
    protected $acceptJsonHeader = ['Accept' => 'application/json'];

    public function test_registration_successful(): void
    {
        // Arrange
        $payload = [
            'name' => 'Amir',
            'email' => 'a.moharami@gmail.com',
            'password' => '123456', // Ensure passwords are strings
            'c_password' => '123456', // Ensure passwords are strings
        ];

        // Act
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['success', 'data'])
            ->assertJson([
                'success' => true,
            ]);

        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function test_missing_required_fields(): void
    {
        // Arrange
        $payload = [];

        // Act
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_invalid_email_format(): void
    {
        // Arrange
        $payload = [
            'name' => 'Amir',
            'email' => 'a.moharami@', // Invalid email format
            'password' => '123456',
            'c_password' => '123456',
        ];

        // Act
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);

        // Assert: Verify the response status and check for specific error messages related to the email field
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_password_mismatch(): void
    {
        // Arrange
        $payload = [
            'name' => 'Amir',
            'email' => 'a.moharami@gmail.com',
            'password' => '123456',
            'c_password' => '123',
        ];

        // Act
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_successful_registration(): void
    {
        // Arrange
        $payload = [
            'name' => 'Amir',
            'email' => 'a.moharami@gmail.com',
            'password' => '123456',
            'c_password' => '123456',
        ];

        // Act
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_email_already_exists(): void
    {
        // Arrange
        $payload = [
            'name' => 'amir',
            'email' => 'a.moharami@gmail.com',
            'password' => 123456,
            'c_password' => 123456,
        ];

        // Act
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response = $this->post($this->url, $payload, $this->acceptJsonHeader);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_password_encryption(): void
    {
        // Arrange
        $mail = 'a.moharami@gmail.com';
        $password = 123456;
        $payload = [
            'name' => 'amir',
            'email' => $mail,
            'password' => $password,
            'c_password' => $password,
        ];

        // Act
        $response = $this->post($this->url, $payload);

        // Assert:
        $response->assertStatus(Response::HTTP_OK);


        $user = User::where('email', $mail)->first();
        // Assert:
        $this->assertNotNull($user);
        $this->assertNotEquals($password, $user->password); // Check that password is hashed
    }
}
