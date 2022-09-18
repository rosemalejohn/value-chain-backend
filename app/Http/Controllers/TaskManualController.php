<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $task->manuals()->syncWithoutDetaching($request->manual_id);

        $task->load('manuals');

        return new TaskResource($task);
    }

    /**
     * Delete task manual
     */
    public function destroy(Task $task, int $taskManualId)
    {
        DB::table('task_manuals')
            ->where('task_id', $task->id)
            ->whereId($taskManualId)
            ->delete();

        return $this->respondWithEmptyData();
    }
}
