<?php

namespace App\Http\Requests\Okr;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeyResultRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'target' => ['nullable', 'numeric'],
            'current' => ['nullable', 'numeric'],
            'unit' => ['nullable', 'string', 'max:50'],
        ];
    }
}
