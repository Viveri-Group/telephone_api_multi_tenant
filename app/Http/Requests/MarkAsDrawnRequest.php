<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkAsDrawnRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'drawn_by' => ['string', 'nullable']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
