<?php

namespace App\Http\Requests;

use App\Enums\TaskAssignmentRole;
use App\Enums\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'outcome' => 'sometimes|nullable|string',
            'priority' => [
                'sometimes',
                'required',
                new Enum(TaskPriority::class),
            ],
            'due_date' => [
                'sometimes',
                'nullable',
                'date',
                'date_format:Y-m-d',
            ],
            'members' => [
                'sometimes',
                'array',
            ],
            'members.*.user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'members.*.role' => [
                'required',
                new Enum(TaskAssignmentRole::class),
            ],
        ];
    }
}
