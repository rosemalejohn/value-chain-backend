<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement',
        'checked_at',
    ];

    protected $dates = [
        'checked_at',
    ];

    /**
     * Task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
