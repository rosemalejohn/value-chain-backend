<?php

namespace App\Http\Requests;

use App\Enums\TaskAssignmentRole;
use App\Enums\TaskImpact;
use App\Enums\TaskPriority;
use App\Enums\TaskStep;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
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
        if ($this->route('task')) {
            return $this->user()->can('update', $this->route('task'));
        }

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
            'initiator_id' => [
                'sometimes',
                'required',
                Rule::exists('users', 'id'),
            ],
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'outcome' => 'sometimes|nullable|string',
            'priority' => [
                'sometimes',
                'required',
                new Enum(TaskPriority::class),
            ],
            'impact' => [
                'sometimes',
                'required',
                new Enum(TaskImpact::class),
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
            'step' => [
                new Enum(TaskStep::class),
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $this->merge([
            'formatted_members' => Arr::map(
                Arr::keyBy($this->members, 'user_id'),
                function ($value) {
                    return Arr::only($value, ['role']);
                }
            ),
        ]);
    }
}
