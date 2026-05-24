<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048'],
            'status' => ['nullable', 'string', 'max:50'],
            'tech_stack' => ['nullable', 'string', 'max:255'],
            'cms' => ['nullable', 'string', 'max:255'],
            'ssl_expires_at' => ['nullable', 'date'],
            'resource_id' => ['nullable', 'exists:resources,id'],
        ];
    }
}
