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
            'status' => $this->status,
            'status_text' => optional($this->status)->description(),
            'order' => $this->order,
            'due_date' => $this->due_date,
            'completed_at' => $this->completed_at,
            'archived_at' => $this->archived_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Relationships
            'members' => UserResource::collection($this->whenLoaded('members')),
            'min_members' => $this->when($this->relationLoaded('members'), function () {
                return UserResource::collection($this->members->slice(0, 3));
            }),
        ];
    }
}
