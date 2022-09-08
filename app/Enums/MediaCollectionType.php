<?php

namespace App\Enums;

enum MediaCollectionType: string
{
    case Avatar = 'avatar';
    case TaskAttachments = 'task_attachments';
    case ManualFile = 'manual_file';
}
