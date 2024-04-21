<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Repository\Task\TaskRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CompleteTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete tasks that have passed 2 days';

    public function __construct(TaskRepository $taskRepository)
    {
        parent::__construct();
        $this->taskRepository = $taskRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $tasks = $this->taskRepository->getTasksCreatedBeforeDaysAndNotCompleted(2);

        foreach ($tasks as $task) {
            $this->taskRepository->completeTask($task);
        }

        $this->info(count($tasks) . ' tasks completed');
    }
}
