<?php

namespace App\Action\File;

use Illuminate\Support\Collection;

class FormatAudioAction
{
    public function __construct(public array $expectedFileTypes)
    {
    }

    public function handle(array $response, Collection $audioData): array
    {
        $audioData
            ->pluck('external_id', 'type')
            ->each(function ($externalId, $type) use (&$response) {
                if (in_array($type, $this->expectedFileTypes) && !array_key_exists($type, $response)) {
                    $response[$type] = $externalId;
                }
            });

        return $response;
    }
}
