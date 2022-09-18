<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementResource extends JsonResource
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
            'measurement' => $this->measurement,
            'task_measurement' => $this->whenPivotLoaded('task_measurements', function () {
                return [
                    'checked_at' => $this->pivot->checked_at,
                    'is_checked' => filled($this->pivot->checked_at),
                ];
            }),
        ];
    }
}
