<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => new AvatarResource($this->whenLoaded('avatar')),
            'placeholder_avatar' => "https://via.placeholder.com/150?text={$this->name}",
            'is_admin' => $this->hasRole(UserRole::Admin->value),
            'created_at' => $this->created_at,
            // Relationships
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'task_member' => $this->whenPivotLoaded('task_members', function () {
                return [
                    'role' => $this->pivot->role,
                ];
            }),
            // Counts
            'created_tasks_count' => $this->when(isset($this->created_tasks_count), $this->created_tasks_count),
            'accepted_created_tasks_count' => $this->when(isset($this->accepted_created_tasks_count), $this->accepted_created_tasks_count),
            'pending_created_tasks_count' => $this->when(isset($this->pending_created_tasks_count), $this->pending_created_tasks_count),
        ];
    }
}
