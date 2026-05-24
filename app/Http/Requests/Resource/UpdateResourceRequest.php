<?php

namespace App\Http\Requests\Resource;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'resource_type_id' => ['nullable', 'exists:resource_types,id'],
            'type' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048'],
            'owner_team_id' => ['nullable', 'exists:teams,id'],
            'status' => ['nullable', 'string', 'max:50'],
            'expires_at' => ['nullable', 'date'],
            'cost_monthly' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
