<?php

namespace App\Http\Requests;

use App\Enums\CompetitionSpecialOffer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CompetitionRequest extends FormRequest
{
    public function rules(): array
    {
        $competition = $this->route('competition');

        if($competition){
            return [
                'name' => ['required', 'string'],
                'start' => ['required', 'date', 'before:end'],
                'end' => ['required', 'date', 'after:start'],
                'special_offer' => ['nullable', 'string', new Enum(CompetitionSpecialOffer::class)],
            ];
        }

        return [
        'organisation_id' => ['required','integer'],
        'name' => ['required', 'string'],
        'start' => ['required', 'date', 'before:end'],
        'end' => ['required', 'date', 'after:start'],
        'special_offer' => ['nullable', 'string', new Enum(CompetitionSpecialOffer::class)],
    ];

    }

    public function authorize(): bool
    {
        return true;
    }
}
