<?php

namespace App\Observers;

use App\Models\Task;
use App\Events\TaskOverdue;

/**
 * Observes task model events.
 *
 * This observer reacts to task lifecycle changes
 * and triggers events when a task becomes overdue.
 */
class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }

    /**
     * Handle the "saved" event for the task model.
     *
     * This method is triggered after a task has been
     * created or updated.
     */
    public function saved(Task $task): void
    {
        if ($task->isOverdue()) {
            event(new TaskOverdue($task));
        }
    }

}
