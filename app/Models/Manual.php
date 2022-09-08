<?php

namespace App\Models;

use App\Enums\MediaCollectionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Manual extends Model
{
    use HasFactory;

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
}
