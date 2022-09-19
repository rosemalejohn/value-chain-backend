<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskMeasurement;
use App\Http\Resources\TaskResource;
use App\Models\Measurement;
use App\Models\Task;
use App\Models\TaskMeasurement;

class TaskMeasurementController extends Controller
{
    /**
     * Create task measurement
     */
    public function store(StoreTaskMeasurement $request, Task $task)
    {
        $measurementId = $request->measurement_id;
        if (is_null($measurementId)) {
            $measurement = Measurement::create(
                $request->only('measurement')
            );
            $measurementId = $measurement->id;
        }

        $task->measurements()->syncWithoutDetaching($measurementId);
        $task->load('measurements');

        return new TaskResource($task);
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
