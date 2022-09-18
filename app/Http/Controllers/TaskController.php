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
            ->allowedIncludes([
                'members.avatar',
                'initiator',
            ])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('step'),
                AllowedFilter::exact('priority'),
                AllowedFilter::scope('assigned'),
            ])
            ->allowedSorts([
                'title',
                'priority',
                'impact',
                'status'
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
            'impact' => $request->impact,
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
        $task->load(
            'members.avatar',
            'createdBy',
            'attachments',
            'initiator',
            'measurements',
            'children.members.avatar'
        );

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
                'impact',
                'due_date',
                'step',
                'status',
            ])
        );

        $task->save();

        if ($request->has('members')) {
            $task->members()->sync($request->formatted_members);
        }

        $task->load('members');

        return new TaskResource($task);
    }
}
