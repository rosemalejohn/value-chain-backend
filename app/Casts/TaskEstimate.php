<?php

namespace App\Casts;

use App\Enums\PeriodType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;
use stdClass;

class TaskEstimate implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        $arr = preg_split('#(?<=\d)(?=[a-z])#i', $value);

        $obj = new stdClass();
        $obj->duration = data_get($arr, 0);
        $obj->period = data_get($arr, 1);

        if ($obj->period && $obj->period !== 'null') {
            $obj->period_text = PeriodType::from($obj->period)->description();
            $obj->period_text = Str::plural($obj->period_text, $obj->duration);
        }

        return $obj;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
