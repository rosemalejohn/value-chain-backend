<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskMeasurement extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole([
            UserRole::Measurement->value,
            UserRole::Admin->value,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'measurement_id' => [
                Rule::requiredIf(is_null($this->measurement)),
                'exists:measurements,id',
            ],
            'measurement' => [
                Rule::requiredIf(is_null($this->measurement_id)),
            ],
            'is_checked' => 'sometimes|required|boolean',
        ];
    }
}
