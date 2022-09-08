<?php

namespace App\Models;

use App\Enums\MediaCollectionType;
use App\Enums\TaskImpact;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Enums\TaskStep;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Task extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = [
        'initiator_id',
        'title',
        'description',
        'outcome',
        'priority',
        'impact',
        'status',
        'step',
        'order',
        'due_date',
        'estimate',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'step' => TaskStep::class,
        'impact' => TaskImpact::class,
    ];

    protected $dates = [
        'due_date',
        'completed_at',
        'archived_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Media Collections
    |--------------------------------------------------------------------------
     */

    /**
     * Register media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::TaskAttachments->value);
    }

    /**
     * Task members
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_members')
            ->withPivot('role');
    }

    /**
     * Task creator
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Task initiator
     */
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    /**
     * Task list of measurements
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(TaskMeasurement::class);
    }

    /**
     * Task manuals
     */
    public function manuals(): BelongsToMany
    {
        return $this->belongsToMany(Manual::class);
    }

    /**
     * Check if user is owner of task
     */
    public function isOwner(User $user): bool
    {
        return $user->created_by === $user->id;
    }

    /**
     * Attachments
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::TaskAttachments->value);
    }

    /**
     * Get accepted tasks
     */
    public function scopeAccepted(Builder $query)
    {
        $query->whereStatus(TaskStatus::Accepted);
    }

    /**
     * Return task for current user only
     */
    public function scopeForCurrentUser(Builder $query): void
    {
        if (! auth()->user()->hasRole('admin')) {
            $query->where(function ($query) {
                $query
                    ->whereHas('members', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->orWhere('created_by', auth()->id());
            });
        }
    }
}
