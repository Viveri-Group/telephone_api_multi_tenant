<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiveCallCapacityCheckRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'call_id' => ['required', 'integer'],
            'phone_number' => ['required', 'string'],
            'caller_phone_number' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
