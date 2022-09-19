<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeasurementResource;
use App\Models\Measurement;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MeasurementController extends Controller
{
    /**
     * Get list of measurements
     */
    public function index()
    {
        $measurements = QueryBuilder::for(Measurement::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
            ])
            ->paginate(request('limit', 10));

        return MeasurementResource::collection($measurements);
    }
}
