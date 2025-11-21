<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiveCallRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'competition_id' => ['required', 'integer'],
            'phone_number' => ['required', 'string'],
            'caller_phone_number' => ['required', 'string', 'unique:App\Models\ActiveCall,caller_phone_number'],
            'call_id' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
