<?php

namespace App\Http\Requests\Okr;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObjectiveRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'max:255'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'quarter' => ['nullable', 'integer', 'min:1', 'max:4'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ];
    }
}
