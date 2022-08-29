<?php

namespace App\Http\Controllers;

use App\Enums\TaskStepStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Tasks
     */
    public function index(): AnonymousResourceCollection
    {
        $tasks = QueryBuilder::for(Task::class)
            ->forCurrentUser()
            ->allowedIncludes([
                'members.avatar',
                'initiator',
            ])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('priority'),
            ])
            ->allowedSorts([
                'title',
                'priority',
            ])
            ->paginate(request('perPage', 20));

        return TaskResource::collection($tasks);
    }

    /**
     * Create a new task
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = $request->user()->createdTasks()->create([
            'initiator_id' => $request->initiator_id,
            'title' => $request->title,
            'description' => $request->description,
            'outcome' => $request->outcome,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'order' => 1,
        ]);

        if ($request->has('members')) {
            $task->members()->sync($request->formatted_members);
        }

        $task->load('members');

        return new TaskResource($task);
    }

    /**
     * View task information
     */
    public function show(Task $task): TaskResource
    {
        $task->load('members.avatar', 'createdBy', 'attachments', 'initiator');

        return new TaskResource($task);
    }

    /**
     * Update task
     */
    public function update(StoreTaskRequest $request, Task $task): TaskResource
    {
        $task->fill(
            $request->only([
                'initiator_id',
                'title',
                'description',
                'outcome',
                'priority',
                'due_date',
                'step',
                'step_status',
            ])
        );

        if ($task->isDirty('step')) {
            $task->step_status = TaskStepStatus::Pending->value;
        }

        $task->save();

        if ($request->has('members')) {
            $task->members()->sync($request->formatted_members);
        }

        $task->load('members');

        return new TaskResource($task);
    }
}
