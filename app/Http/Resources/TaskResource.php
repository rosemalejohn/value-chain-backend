<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'title' => $this->title,
            'description' => $this->description,
            'short_description' => Str::limit(strip_tags($this->description), 75),
            'outcome' => $this->outcome,
            'url' => $this->url,
            'priority' => $this->priority,
            'priority_text' => optional($this->priority)->description(),
            'impact' => $this->impact,
            'impact_text' => optional($this->impact)->description(),
            'status' => $this->status,
            'step' => $this->step,
            'from_step' => $this->from_step,
            'step_text' => optional($this->step)->description(),
            'from_step_text' => optional($this->from_step)->description(),
            'status_text' => optional($this->status)->description(),
            'order' => $this->order,
            'remarks' => $this->remarks,
            'due_date' => optional($this->due_date)->format('Y-m-d'),
            'estimate' => $this->estimate,
            'estimate_duration' => optional($this->estimate)->duration,
            'estimate_period' => optional($this->estimate)->period,
            'total_duration' => $this->total_duration,
            'is_completed' => filled($this->completed_at),
            'completed_at' => $this->completed_at,
            'archived_at' => $this->archived_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Relationships
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'initiator' => new UserResource($this->whenLoaded('initiator')),
            'attachments' => MediaResource::collection($this->whenLoaded('attachments')),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'min_members' => $this->when($this->relationLoaded('members'), function () {
                return UserResource::collection($this->members->slice(0, 3));
            }),
            'parent' => new static($this->whenLoaded('parent')),
            'measurements' => MeasurementResource::collection($this->whenLoaded('measurements')),
            'subtasks' => self::collection($this->whenLoaded('children')),
            'manuals' => ManualResource::collection($this->whenLoaded('manuals')),
            'abtests' => TaskAbTestResource::collection($this->whenLoaded('abtests')),
            'links' => TaskLinkResource::collection($this->whenLoaded('links')),
            // Computed
            'is_subtask' => filled($this->parent_id),
            'is_step_forward' => $this->isStepForward(),
        ];
    }
}
