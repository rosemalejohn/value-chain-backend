<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'title' => $this->title,
            'description' => $this->description,
            'outcome' => $this->outcome,
            'priority' => $this->priority,
            'priority_text' => optional($this->priority)->description(),
            'impact' => $this->impact,
            'impact_text' => optional($this->impact)->description(),
            'status' => $this->status,
            'step' => $this->step,
            'status_text' => optional($this->status)->description(),
            'order' => $this->order,
            'due_date' => optional($this->due_date)->format('Y-m-d'),
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
            'measurements' => MeasurementResource::collection($this->whenLoaded('measurements')),
            'subtasks' => self::collection($this->whenLoaded('children')),
            'manuals' => ManualResource::collection($this->whenLoaded('manuals')),
            // Computed
            'is_subtask' => filled($this->parent_id),
        ];
    }
}
