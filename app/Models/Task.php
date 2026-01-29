<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'deadline' => 'datetime'
    ];

    protected $fillable = [
        'title',
        'description',
        'status',
        'deadline',
        'project_id',
        'user_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
       return $this->deadline !== null && $this->deadline->isPast();
    }
}
