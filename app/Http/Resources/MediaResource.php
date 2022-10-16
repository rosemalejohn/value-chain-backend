<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'file_name' => $this->file_name,
            'url' => $this->getUrl(),
            'type' => $this->getFileType(),
        ];
    }

    /**
     * Check if video or image or file
     */
    private function getFileType(): string
    {
        if (strstr($this->mime_type, 'video/')) {
            return 'video';
        } elseif (strstr($this->mime_type, 'image/')) {
            return 'image';
        } else {
            return 'file';
        }
    }
}
