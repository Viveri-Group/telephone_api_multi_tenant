<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetAltCompetitionWinnerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'position' => ['required', 'integer', 'gte:1'],
            'date_from' => ['required', 'date', 'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:Z|[+-]\d{2}:\d{2})$/', 'before_or_equal:date_to'], // 2023-12-05T14:30:59Z
            'date_to' => ['required', 'date', 'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:Z|[+-]\d{2}:\d{2})$/'], // 2023-12-05T14:30:59Z
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
