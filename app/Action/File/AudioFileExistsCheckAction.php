<?php

namespace App\Action\File;

use App\Models\Competition;
use App\Models\FileUpload;

class AudioFileExistsCheckAction
{
    public function handle(array $data, Competition $competition): bool
    {
        return match ($data['audio_type']) {
            'competition' => FileUpload::where([
                'competition_id' => $competition->id,
                'type' => $data['type']
            ])->exists(),

            'competition_phone_line' => FileUpload::where([
                'competition_phone_line_id' => $data['competition_phone_line_id'],
                'type' => $data['type']
            ])->exists(),

            default => false,
        };
    }
}
