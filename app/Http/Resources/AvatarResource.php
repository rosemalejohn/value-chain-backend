<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvatarResource extends JsonResource
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
            'url' => $this->getUrl(),
            $this->mergeWhen($this->hasGeneratedConversion('thumb'), function () {
                return [
                    'thumb_url' => $this->getUrl('thumb'),
                ];
            }),
        ];
    }
}
