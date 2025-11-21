<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallerHistoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'caller_phone_number' => ['required', 'string', 'regex:/^[0-9]{12}$/']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
