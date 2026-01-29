<?php

namespace App\Listeners;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TaskOverdue;
use App\Notifications\TaskOverdueNotification;

class HandleOverdueTask
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskOverdue $event): void
    {
        $user = $event->task->user;

        $user->notify(new TaskOverdueNotification($event->task));
    }
}
