<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskSubtaskController extends Controller
{
    /**
     * Add subtask for when QA found bugs
     */
    public function store(StoreSubtaskRequest $request, Task $task): TaskResource
    {
        $subtask = $task->children()->create([
            'created_by' => auth()->id(),
            'initiator_id' => auth()->id(),
            ...$request->only([
                'title',
                'description',
            ]),
        ]);

        // @todo assign members

        return new TaskResource($subtask);
    }
}
