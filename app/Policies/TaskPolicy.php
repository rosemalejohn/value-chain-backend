<?php

namespace App\Policies;

use App\Enums\TaskAssignmentRole;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user can update task
     */
    public function update(Authenticatable $user, Task $task)
    {
        $isManager = $task->members()
            ->whereId($user->id)
            ->wherePivot('role', TaskAssignmentRole::Manager->value)
            ->exists();

        return $task->isOwner($user) || $isManager;
    }
}
