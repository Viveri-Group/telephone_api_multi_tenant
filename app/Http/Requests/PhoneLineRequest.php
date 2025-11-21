<?php

namespace App\Http\Requests;

use App\Rules\PhoneLineInUseCheckRule;
use App\Rules\PhoneLineIsInPhoneBookCheckRule;
use App\Rules\UniquePhoneLineToCompetitionCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class PhoneLineRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => [
                'required',
                'string',
                new PhoneLineIsInPhoneBookCheckRule(),
                new UniquePhoneLineToCompetitionCheckRule($this->competition),
                new PhoneLineInUseCheckRule($this->competition),
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
