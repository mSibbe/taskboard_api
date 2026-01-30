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

/**
 * Event that is dispatched when a task becomes overdue.
 *
 * This event carries the affected task instance and
 * can be handled by listeners (e.g. notifications).
 */
class TaskOverdue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The overdue task instance.
     */
    public Task $task;

    /**
     * Create a new event instance.
     *
     * @param Task $task The overdue task instance
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
