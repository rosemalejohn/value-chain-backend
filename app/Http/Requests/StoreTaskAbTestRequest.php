<?php

namespace App\Http\Requests;

use App\Enums\TaskAbTestGroup;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTaskAbTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole(UserRole::AbTester->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'group' => [
                'required',
                new Enum(TaskAbTestGroup::class),
            ],
            'description' => 'required',
        ];
    }
}
