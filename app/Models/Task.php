<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'outcome',
        'priority',
        'due_date',
        'order',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
    ];

    protected $dates = [
        'due_date',
        'completed_at',
        'archived_at',
    ];

    /**
     * Task members
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_members');
    }

    /**
     * Check if user is owner of task
     */
    public function isOwner(User $user): bool
    {
        return $user->created_by === $user->id;
    }
}
