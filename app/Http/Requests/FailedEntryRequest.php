<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FailedEntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'string'],
            'caller_phone_number' => ['required', 'string'],
            'reason' => ['required', 'string'],
            'call_start' => ['required', 'date'],
            'call_id' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
