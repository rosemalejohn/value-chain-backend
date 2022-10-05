<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManualResource extends JsonResource
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
            'file_attachment' => new MediaResource($this->whenLoaded('fileAttachment')),
            'task_manual' => $this->whenPivotLoaded('task_manuals', function () {
                return [
                    'id' => $this->pivot->id,
                ];
            }),
        ];
    }
}
