<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskMeasurement;
use App\Http\Resources\TaskMeasurementResource;
use App\Models\Task;
use App\Models\TaskMeasurement;
use Illuminate\Http\Request;

class TaskMeasurementController extends Controller
{
    /**
     * Create task measurement
     */
    public function store(StoreTaskMeasurement $request, Task $task)
    {
        $taskMeasurement = $task->measurements()->create([
            'measurement' => $request->measurement,
            'checked_at' => $request->is_checked ? now() : null,
        ]);

        return new TaskMeasurementResource($taskMeasurement);
    }

    /**
     * Update task measurement
     */
    public function update(Request $request, Task $task, TaskMeasurement $taskMeasurement)
    {
        if ($request->has('is_checked')) {
            $taskMeasurement->checked_at = $request->is_checked ? now() : null;
        }

        $taskMeasurement->save();
    }

    /**
     * Remove task measurement
     */
    public function destroy(Task $task, TaskMeasurement $taskMeasurement)
    {
        $this->authorize('update', $taskMeasurement);

        $taskMeasurement->delete();

        return $this->respondWithEmptyData();
    }
}
