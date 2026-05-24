<?php

namespace App\Http\Requests\Okr;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOkrCheckinRequest extends FormRequest
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
            'value' => ['sometimes', 'numeric'],
            'note' => ['nullable', 'string', 'max:2000'],
            'checked_at' => ['nullable', 'date'],
        ];
    }
}
