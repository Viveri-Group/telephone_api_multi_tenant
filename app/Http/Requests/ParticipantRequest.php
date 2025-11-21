<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'telephone' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
