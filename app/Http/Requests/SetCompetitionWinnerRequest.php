<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetCompetitionWinnerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'position' => ['required', 'integer', 'gte:1'],
            'round_hash' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
