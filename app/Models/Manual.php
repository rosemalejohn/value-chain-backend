<?php

namespace App\Models;

use App\Enums\MediaCollectionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Manual extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /*
    |--------------------------------------------------------------------------
    | Media Collections
    |--------------------------------------------------------------------------
    */

    /**
     * Register media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::ManualFile->value)
            ->singleFile();
    }

    /**
     * File
     */
    public function fileAttachment(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::ManualFile->value);
    }

    /**
     * Search by title or description
     */
    public function scopeSearch(Builder $query, string $search = ''): void
    {
        $query->where(function ($query) use ($search) {
            $query->where('title', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
        });
    }
}
