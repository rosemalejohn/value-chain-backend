<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskManualController extends Controller
{
    /**
     * Add manual on task
     */
    public function store(Request $request, Task $task): TaskResource
    {
        $this->validate($request, [
            'manual_id' => [
                'required',
                'exists:manuals,id',
            ],
        ]);

        $task->manuals()->attach($request->manual_id);

        $task->load('manuals');

        return new TaskResource($task);
    }

    /**
     * Delete task manual
     */
    public function destroy(Task $task, int $taskManualId)
    {
    }
}
