<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskStatusController extends Controller
{
    /**
     * Update task status
     */
    public function __invoke(UpdateTaskStatusRequest $request, Task $task): TaskResource
    {
        $task->status = $request->status;
        $task->save();

        return new TaskResource($task);
    }
}
