<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * Represents a task entity.
 *
 * A task belongs to a user, may belong to a project,
 * and can contain deadline-based business logic.
 */
class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'deadline',
        'project_id',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'deadline' => 'datetime'
    ];

    /**
     * Get the project this task belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine whether the task is overdue.
     *
     * A task is considered overdue if a deadline is set
     * and the deadline is in the past.
     */
    public function isOverdue(): bool
    {
       return $this->deadline !== null && $this->deadline->isPast();
    }
}
