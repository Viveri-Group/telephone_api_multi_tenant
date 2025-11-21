<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneBookEntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'string', 'unique:phone_book_entries,phone_number'],
            'name' => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
