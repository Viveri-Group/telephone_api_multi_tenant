<?php

namespace App\Http\Resources\Download;

use App\Enums\CliPresentation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->uuid,
            'telephone' => $this->cli_presentation === CliPresentation::PI_ALLOW_PRESENTATION->value ? $this->telephone : config('system.DOWNLOAD.DEFAULT_HIDDEN_CLI_PRESENTATION'),
            'call_start' => $this->call_start,
        ];
    }
}
