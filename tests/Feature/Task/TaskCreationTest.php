<?php

namespace Tests\Feature\Task;

use App\Enum\StatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;


class TaskCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task()
    {
        // Arrange
        $user = User::factory()->create();

        $payload = [
            'name' => 'Task Name',
            'description' => 'Task Description',
            'status' => StatusEnum::PENDING,
        ];

        // Act
        $response = $this->actingAs($user)->postJson('/api/tasks', $payload);

        // Assert
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['success', 'data'])
            ->assertJson([
                'success' => true,
            ]);


        $this->assertDatabaseHas('tasks', ['name' => 'Task Name']);
    }

    public function test_task_creation_requires_valid_data()
    {
        // Arrange
        $user = User::factory()->create(); // Create a user

        $payload = [
            'name' => 123,
            'description' => 'Task Description',
            'status' => StatusEnum::PENDING,
        ];
        // Act
        $response = $this->actingAs($user)->postJson('/api/tasks', $payload);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_task_creation_requires_valid_status_data()
    {
        // Arrange
        $user = User::factory()->create(); // Create a user

        $payload = [
            'name' => 123,
            'description' => 'Task Description',
            'status' => 'unknown-status',
        ];
        // Act
        $response = $this->actingAs($user)->postJson('/api/tasks', $payload);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_user_can_retrieve_tasks()
    {
        // Arrange
        $user = User::factory()->create();
        $tasks = Task::factory(3)->create(['user_id' => $user->id]);

        // ACT
        $response = $this->actingAs($user)->get('/api/tasks');

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['success', 'data'])
            ->assertJson([
                'success' => true,
            ]);


        foreach ($tasks as $task) {
            $response->assertJsonFragment([
                'id' => $task->id,
                'name' => $task->name,
                'description' => $task->description,
                'status' => $task->status,
            ]);
        }
    }


    public function test_user_can_retrieve_specific_task()
    {
        // Arrange
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Act
        $response = $this->actingAs($user)->get('/api/tasks/' . $task->id);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['success', 'data'])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonFragment([
                'id' => $task->id,
                'name' => $task->name,
                'description' => $task->description,
                'status' => $task->status,
            ]);
    }


    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'name' => 'Updated Task Name',
            'description' => 'Updated Task Description',
            'status' => StatusEnum::COMPLETED,
        ];

        $response = $this->actingAs($user)->putJson('/api/tasks/' . $task->id, $updatedData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['success', 'data'])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonFragment([
                'name' => $updatedData['name'],
                'description' => $updatedData['description'],
                'status' => $updatedData['status'],
            ]);

        // Check if the task was updated in the database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => $updatedData['name'],
            'description' => $updatedData['description'],
            'status' => $updatedData['status'],
        ]);
    }


    public function test_user_cannot_update_another_user_task()
    {
        // Create two different users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a task for user1
        $task = Task::factory()->create(['user_id' => $user1->id]);

        // Data to update the task
        $updatedData = [
            'name' => 'Updated Task Name',
            'description' => 'Updated Task Description',
            'status' => 'completed',
        ];

        // Attempt to update the task of user1 with user2 credentials
        $response = $this->actingAs($user2)->putJson('/api/tasks/' . $task->id, $updatedData);

        // Assert that the update request is forbidden
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }


}