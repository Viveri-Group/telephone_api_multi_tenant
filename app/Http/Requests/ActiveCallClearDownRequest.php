<?php

namespace App\Http\Requests;

use App\Enums\ClearDownStatus;
use App\Rules\BooleanOrStringBooleanCheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActiveCallClearDownRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(ClearDownStatus::class)],
            'reason' => ['required_if:type,fail', 'string'],
            'sms_offer_accepted' => [new BooleanOrStringBooleanCheckRule()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
