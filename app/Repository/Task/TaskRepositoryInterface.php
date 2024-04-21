<?php

namespace App\Repository\Task;

interface TaskRepositoryInterface
{
    public function allTasksByUserId($userId);

    public function createTask($data);

    public function findTaskById($taskId);

    public function updateTask($task, $data);

    public function deleteTask($task);
}