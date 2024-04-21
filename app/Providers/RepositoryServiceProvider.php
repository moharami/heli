<?php

namespace App\Providers;

use App\Repository\Task\TaskRepository;
use App\Repository\Task\TaskRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class,TaskRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
