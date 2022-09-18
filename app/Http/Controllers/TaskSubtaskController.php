<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
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
            'order' => 1,
            'status' => TaskStatus::Accepted,
            ...$request->only([
                'title',
                'description',
            ]),
        ]);

        // Assign same members
        $subtask->members()->sync(
            $task->members()
                ->get()
                ->mapWithKeys(function ($member) {
                    return [
                        $member->id => $member->pivot->only('role'),
                    ];
                })
                ->toArray()
        );

        // @todo assign members

        return new TaskResource($subtask);
    }
}
