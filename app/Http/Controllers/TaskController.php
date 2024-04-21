<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Repository\Task\TaskRepository;
use App\Repository\Task\TaskRepositoryInterface;

class TaskController extends BaseController
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        $tasks = $this->taskRepository->allTasksByUserId(auth()->user()->id);
        return $this->sendResponse(TaskResource::collection($tasks), 'Tasks retrieved');
    }


    public function store(TaskStoreRequest $request)
    {
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'user_id' => auth()->user()->id,
        ];

        $task = $this->taskRepository->createTask($data);
        return $this->sendCreated(new TaskResource($task), 'Task created');
    }


    public function show($taskId)
    {
        $task = $this->taskRepository->findTaskById($taskId);
        return $this->sendResponse(new TaskResource($task), 'Task retrieved');
    }

    public function update(TaskUpdateRequest $request, $taskId)
    {
        $task = $this->taskRepository->findTaskById($taskId);

        if ($request->user()->id !== $task->user_id) {
            return response()->json(['error' => ''], 403);
        }

        $data = $request->only(['name', 'description', 'status']);
        $task = $this->taskRepository->updateTask($task, $data);

        return $this->sendResponse(new TaskResource($task), 'Task updated');
    }

    public function destroy($taskId)
    {
        $task = $this->taskRepository->findTaskById($taskId);
        $this->taskRepository->deleteTask($task);

        return $this->sendResponse(null, 'Task deleted');
    }
}
