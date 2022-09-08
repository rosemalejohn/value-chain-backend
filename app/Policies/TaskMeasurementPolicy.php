<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\TaskMeasurement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class TaskMeasurementPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user can create task measurement
     */
    public function create(Authenticatable $user, TaskMeasurement $taskMeasurement)
    {
        return $user->hasRole(UserRole::Measurement->value);
    }

    /**
     * Check if auth user can update task measurement
     */
    public function update(Authenticatable $user, TaskMeasurement $taskMeasurement)
    {
        return $user->hasRole(UserRole::Measurement->value);
    }
}
