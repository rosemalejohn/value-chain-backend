<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link',
    ];

    /**
     * Task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
