<?php

namespace App\Repository\Task;

use App\Enum\StatusEnum;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TaskRepository implements TaskRepositoryInterface
{
    public function allTasksByUserId($userId)
    {
        return Task::where('user_id', $userId)->get();
    }

    public function createTask($data)
    {
        return Task::create($data);
    }

    public function findTaskById($taskId)
    {
        return Task::findOrFail($taskId);
    }

    public function updateTask($task, $data)
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask($task)
    {
        $task->delete();
    }

    public function getTasksCreatedBeforeDaysAndNotCompleted($days)
    {
        return Task::where('created_at', '<=', Carbon::now()->subDays($days))
            ->where('status', '!=', StatusEnum::COMPLETED)
            ->get();
    }

    public function completeTask(Task $task)
    {
        $task->update(['status' => StatusEnum::COMPLETED]);
    }

}