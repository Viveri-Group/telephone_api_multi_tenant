<?php

namespace App\Http\Requests;

use App\Enums\CliPresentation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CompetitionCheckRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cli_presentation' =>  ['sometimes', 'integer', new Enum(CliPresentation::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
