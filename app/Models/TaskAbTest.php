<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAbTest extends Model
{
    use HasFactory;

    protected $table = 'task_abtests';

    protected $fillable = [
        'group',
        'description',
    ];

    /**
     * Task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
