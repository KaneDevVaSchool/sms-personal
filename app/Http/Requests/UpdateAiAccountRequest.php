<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAiAccountRequest extends FormRequest
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
            'service' => ['sometimes', 'string', 'max:255'],
            'label' => ['sometimes', 'string', 'max:255'],
            'api_key' => ['nullable', 'string'],
            'api_key_hint' => ['nullable', 'string', 'max:255'],
            'quota_limit' => ['nullable', 'numeric'],
            'quota_used' => ['nullable', 'numeric'],
            'billing_date' => ['nullable', 'date'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
        ];
    }
}
