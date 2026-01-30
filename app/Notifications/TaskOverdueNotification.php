<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

/**
 * Notification that informs a user about an overdue task.
 *
 * This notification is stored in the database and can be
 * retrieved via the notifications API endpoint.
 */
class TaskOverdueNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * This notification is delivered via the database channel.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     *
     * This data will be stored in the notifications table
     * and can be retrieved by the authenticated user.
     */
    public function toDatabase($notifiable){
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => 'Task "'. $this->task->title.'" is overdue.'
        ];
    }
}
