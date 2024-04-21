<?php

namespace Tests\Console\Commands;

use App\Enum\StatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class CompleteTasksTest extends TestCase
{
    use RefreshDatabase;

    public function testCompleteTasksCommand()
    {
        $user = User::factory()->create();

        $tasksToComplete = Task::factory()->count(3)->pending()->forUser($user->id)->create(['created_at' => now()->subDays(3)]);

        // Run the artisan command
        $this->artisan('tasks:complete');

        // Assert that completed tasks have is_completed set to true
        foreach ($tasksToComplete as $task) {
            $this->assertTrue($task->fresh()->status == StatusEnum::COMPLETED);
        }
    }

    public function testCompleteAndNotCompleteTasksCommand()
    {
        $user = User::factory()->create();
        $countTaskPending = 10;
        $countTaskPassed = 3;

        Task::factory()->count($countTaskPending)->pending()->forUser($user->id)->create();
        Task::factory()->count($countTaskPassed)->pending()->forUser($user->id)->create(['created_at' => now()->subDays(3)]);

        $this->artisan('tasks:complete');



        $tasksComplete = Task::where('status',StatusEnum::COMPLETED)->count();
        $taskPending = Task::where('status',StatusEnum::PENDING)->count();

        $this->assertEquals($tasksComplete, $countTaskPassed);
        $this->assertEquals($taskPending, $countTaskPending);
    }
}