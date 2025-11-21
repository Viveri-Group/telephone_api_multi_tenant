<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneLineLookupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'string'],
            'caller_number' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
