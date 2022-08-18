<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
            ->allowedIncludes('members.avatar')
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('priority'),
            ])
            ->paginate(request('perPage'));

        return TaskResource::collection($tasks);
    }

    /**
     * Create a new task
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = $request->user()->createdTasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'outcome' => $request->outcome,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'order' => 1,
        ]);

        if ($request->has('members')) {
            $task->members()->sync($request->members);
        }

        $task->load('members');

        return new TaskResource($task);
    }

    /**
     * View task information
     */
    public function show(Task $task): TaskResource
    {
        $task->load('members.avatar', 'createdBy', 'attachments');

        return new TaskResource($task);
    }

    /**
     * Update task
     */
    public function update(StoreTaskRequest $request, Task $task): TaskResource
    {
        $task->fill(
            $request->only([
                'title',
                'description',
                'outcome',
                'priority',
                'due_date',
                'status',
            ])
        );
        $task->save();

        if ($request->has('members')) {
            $task->members()->sync($request->members);
        }

        $task->load('members');

        return new TaskResource($task);
    }
}
