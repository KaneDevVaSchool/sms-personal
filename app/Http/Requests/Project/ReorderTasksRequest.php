<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTasksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'tasks' => ['required', 'array', 'min:1'],
            'tasks.*.id' => ['required', 'integer', 'exists:tasks,id'],
            'tasks.*.sort_order' => ['required', 'integer', 'min:0'],
            'tasks.*.status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
