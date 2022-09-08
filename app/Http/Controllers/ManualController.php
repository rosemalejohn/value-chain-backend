<?php

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\StoreManualRequest;
use App\Http\Resources\ManualResource;
use App\Models\Manual;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ManualController extends Controller
{
    /**
     * List manuals
     */
    public function index()
    {
        $manuals = QueryBuilder::for(Manual::class)
            ->allowedIncludes([
                'fileAttachment',
            ])
            ->allowedFilters([
                AllowedFilter::scope('search'),
            ])
            ->paginate(request('limit', 10));

        return ManualResource::collection($manuals);
    }

    /**
     * Add a new manual
     */
    public function store(StoreManualRequest $request): ManualResource
    {
        $manual = Manual::create(
            $request->only([
                'title',
                'description',
            ])
        );

        $manual->addMedia($request->file_attachment)
            ->toMediaCollection(MediaCollectionType::ManualFile->value);

        $manual->load('fileAttachment');

        return new ManualResource($manual);
    }

    /**
     * Update manual
     */
    public function update(StoreManualRequest $request, Manual $manual)
    {
        $
    }
}
