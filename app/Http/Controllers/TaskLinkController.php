<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskLinkResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskLinkController extends Controller
{
    /**
     * Create task link
     */
    public function store(Request $request, Task $task): TaskLinkResource
    {
        $this->validate($request, [
            'link' => 'required|url',
        ]);

        $link = $task->links()->create(
            $request->only([
                'title',
                'link',
            ])
        );

        return new TaskLinkResource($link);
    }

    /**
     * Remove task link
     */
    public function destroy(Task $task, int $linkId)
    {
        $link = $task->links()->findOrFail($linkId);
        $link->delete();

        return $this->respondWithEmptyData();
    }
}
