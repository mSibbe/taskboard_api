<?php

namespace App\Listeners;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TaskOverdue;
use App\Notifications\TaskOverdueNotification;

/**
 * Listener that handles overdue task events.
 *
 * Sends a notification to the task owner when
 * a task becomes overdue.
 */
class HandleOverdueTask
{
    /**
     * Handle the event.
     *
     * This method is triggered when a TaskOverdue event
     * is dispatched and sends a notification to the task owner.
     */
    public function handle(TaskOverdue $event): void
    {
        $user = $event->task->user;

        $user->notify(new TaskOverdueNotification($event->task));
    }
}
