<?php

namespace App\Http\Requests;

use App\Enums\CompetitionAudioType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AudioFileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:wav', 'max:100000'],
            'type' => ['required', 'string', new Enum(CompetitionAudioType::class)],
            'name' => ['required', 'string'],
            'audio_type' => ['required', 'string', 'in:competition,competition_phone_line'],
            'competition_phone_line_id' => ['required_if:audio_type,competition_phone_line', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
