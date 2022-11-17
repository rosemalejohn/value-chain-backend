<?php

namespace App\Policies;

use App\Enums\TaskAssignmentRole;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
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

        $userRole = optional($task->step)->userRole();

        $canChangeStep = $userRole ? $user->hasRole($userRole->value) : false;

        return $task->isOwner($user) || $isManager || $user->hasRole(UserRole::Admin->value) || $canChangeStep;
    }

    /**
     * Check if user can update task status
     */
    public function updateStatus(Authenticatable $user, Task $task)
    {
        return $user->hasRole(UserRole::Admin->value) && $task->status !== TaskStatus::Accepted;
    }
}
