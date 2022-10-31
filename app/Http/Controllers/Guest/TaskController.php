<?php

namespace App\Http\Controllers\Guest;

use App\Enums\MediaCollectionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Create a new task
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $initiator = User::find($request->initiator_id);

        $task = $initiator->createdTasks()->create([
            'initiator_id' => $request->initiator_id,
            'title' => $request->title,
            'description' => $request->description,
            'outcome' => $request->outcome,
            'priority' => $request->priority,
            'impact' => $request->impact,
            'due_date' => $request->due_date,
            'estimate' => $request->estimate,
            'order' => 1,
        ]);

        return new TaskResource($task);
    }

    /**
     * Upload task attachments
     */
    public function storeAttachments(Request $request, Task $task): MediaResource
    {
        $this->validate($request, [
            'attachment' => 'required|file',
        ]);

        $attachment = $task->addMedia($request->attachment)
            ->toMediaCollection(MediaCollectionType::TaskAttachments->value);

        return new MediaResource($attachment);
    }
}
