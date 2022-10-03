<?php

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Resources\MediaResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskAttachmentController extends Controller
{
    /**
     * Add task attachment
     */
    public function store(Request $request, Task $task): MediaResource
    {
        $this->validate($request, [
            'attachment' => 'required|file',
        ]);

        $attachment = $task->addMedia($request->attachment)
            ->toMediaCollection(MediaCollectionType::TaskAttachments->value);

        return new MediaResource($attachment);
    }

    /**
     * Remove task attachment
     */
    public function destroy(Task $task, int $mediaId)
    {
        $media = $task->attachments()->find($mediaId);
        $media->delete();

        return $this->respondWithEmptyData();
    }
}
