<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskAbTestRequest;
use App\Http\Resources\TaskAbTestResource;
use App\Models\Task;

class TaskAbTestController extends Controller
{
    /**
     * Add task ab testing item
     */
    public function store(StoreTaskAbTestRequest $request, Task $task): TaskAbTestResource
    {
        $abTest = $task->abtests()->create(
            $request->only('group', 'description')
        );

        return new TaskAbTestResource($abTest);
    }

    /**
     * Update ab test
     */
    public function update(StoreTaskAbTestRequest $request, Task $task, $abTestId): TaskAbTestResource
    {
        $test = $task->abtests()->find($abTestId);
        $test->fill($request->only('group', 'description'));
        $test->save();

        return new TaskAbTestResource($test);
    }

    /**
     * Delete ab test item
     */
    public function destroy(Task $task, $abTestId)
    {
        $task->abtests()->where('id', $abTestId)->delete();

        return $this->respondWithEmptyData();
    }
}
