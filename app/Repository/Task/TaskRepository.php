<?php

namespace App\Repository\Task;

use App\Models\Task;

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

}