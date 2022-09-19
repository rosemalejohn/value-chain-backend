<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement',
    ];

    /**
     * Search by measurement
     */
    public function scopeSearch(Builder $query, string $search = ''): void
    {
        $query->where('measurement', 'LIKE', "%$search%");
    }
}
