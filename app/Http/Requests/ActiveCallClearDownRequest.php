<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiveCallClearDownRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'marker' => ['string', 'required'],
            'recordFileNum' => ['integer', 'required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
