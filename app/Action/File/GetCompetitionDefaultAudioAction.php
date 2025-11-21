<?php

namespace App\Action\File;

use App\Models\FileDefault;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GetCompetitionDefaultAudioAction
{
    public function __construct(public array $expectedFileTypes)
    {
    }

    public function handle(array $audioFiles = []): array
    {
        return (new FormatAudioAction($this->expectedFileTypes))->handle($audioFiles, $this->getDefaultAudio());
    }

    protected function getDefaultAudio(): Collection
    {
        return Cache::remember(
            'system_default_audio_files__' . collect($this->expectedFileTypes)->implode('__'),
            now()->addMinute(),
            fn() => FileDefault::whereIn('type', $this->expectedFileTypes)->get()
        );
    }
}
