<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Mail\TaskAccepted;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;

class TaskStatusController extends Controller
{
    /**
     * Update task status
     */
    public function __invoke(UpdateTaskStatusRequest $request, Task $task): TaskResource
    {
        $task->status = $request->status;
        $task->save();

        if ($request->status === TaskStatus::Accepted->value) {
            Mail::to($task->initiator)->queue(new TaskAccepted($task));
        }

        return new TaskResource($task);
    }
}
