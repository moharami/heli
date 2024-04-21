<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function broadcastOn()
    {
        return new Channel('task.'.$this->task->user_id);
    }

    public function broadcastWith()
    {
        return ['taskId' => $this->task->id, 'title' => $this->task->title, 'completed' => $this->task->status];
    }
}
