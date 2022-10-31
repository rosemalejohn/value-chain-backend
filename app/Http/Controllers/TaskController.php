<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\TaskStep;
use App\Http\Requests\DeployTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Mail\TaskAccepted;
use App\Mail\TaskStepUpdated;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
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
            ->with('children')
            ->whereNull('parent_id')
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
            'manuals.fileAttachment',
            'abtests'
        );

        return new TaskResource($task);
    }

    /**
     * Update task
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task = DB::transaction(function () use ($request, $task) {
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
                    'estimate',
                    'remarks',
                ])
            );

            // Mail when task is accepted
            if ($request->has('status') && $task->isDirty('status')) {
                if ($request->status === TaskStatus::Accepted->value) {
                    $task->step = TaskStep::Measurement;

                    Mail::to($task->initiator)->queue(new TaskAccepted($task));
                }
            }

            if ($task->isDirty('step')) {
                $task->from_step = $task->getOriginal('step');

                if ($request->step === TaskStep::Development->value) {
                    $task->status = TaskStatus::Pending;
                }

                $task->save();

                if ($task->isAccepted()) {
                    // Send email to recipients
                    $recipients = User::query()
                        ->whereHas('roles', function ($query) use ($task) {
                            $query->where('name', $task->step->userRole());
                        })
                        ->pluck('email')
                        ->toArray();
                    Mail::to($recipients)->queue(new TaskStepUpdated($task));
                }
            }

            $task->save();

            if ($request->has('members')) {
                $task->members()->sync($request->formatted_members);
            }

            $task->load('members');

            return $task;
        });

        return new TaskResource($task);
    }

    /**
     * Delete task
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return $this->respondWithEmptyData();
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
