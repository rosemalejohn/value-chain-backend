<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeployTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Mail\TaskStepUpdated;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;
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
                'status',
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
            'estimate' => $request->estimate,
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
            'parent',
            'members.avatar',
            'createdBy',
            'attachments',
            'initiator',
            'measurements',
            'children.members.avatar',
            'manuals.fileAttachment'
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

        if ($request->has('step') && $task->isDirty('step')) {
            // Send email to recipients
            $recipients = User::query()
                ->whereHas('roles', function ($query) use ($task) {
                    $query->where('name', $task->step->userRole());
                })
                ->pluck('email')
                ->toArray();
            Mail::to($recipients)->queue(new TaskStepUpdated($task));
        }

        if ($request->has('members')) {
            $task->members()->sync($request->formatted_members);
        }

        $task->load('members');

        return new TaskResource($task);
    }

    /**
     * Deploy task
     */
    public function deploy(DeployTaskRequest $request, Task $task)
    {
        $task->completed_at = now();
        $task->total_duration = $request->total_duration;
        $task->step = null;
        $task->save();

        return new TaskResource($task);
    }
}
