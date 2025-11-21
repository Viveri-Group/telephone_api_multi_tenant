<?php

namespace App\Action\File;

use App\Models\CompetitionPhoneLine;

class GetCompetitionAudioAction
{
    public const array COST_OVERRIDES = [
        '2_00' => [
            'CALL_COST_WARNING' => 'CALL_COST_WARNING_2_00',
            'CAPPING_MESSAGE' => 'CAPPING_MESSAGE_2_00',
        ],
        '2_50' => [
            'CALL_COST_WARNING' => 'CALL_COST_WARNING_2_50',
            'CAPPING_MESSAGE' => 'CAPPING_MESSAGE_2_50',
        ],
    ];

    public const array OVERRIDE_KEYS_TO_REMOVE = [
        'CALL_COST_WARNING_2_00',
        'CAPPING_MESSAGE_2_00',
        'CALL_COST_WARNING_2_50',
        'CAPPING_MESSAGE_2_50',
    ];

    public function __construct(public array $expectedFileTypes)
    {
    }

    public function handle(CompetitionPhoneLine $competitionPhoneLine): array
    {
        $competitionPhoneLine->load('files', 'competition.files');

        // get competition phone line audio first
        $audioFiles = (new FormatAudioAction($this->expectedFileTypes))->handle([], $competitionPhoneLine->files);

        // get competition audio next
        $audioFiles = (new FormatAudioAction($this->expectedFileTypes))->handle($audioFiles, $competitionPhoneLine->competition->files);

        // add in default audio where missing
        $audioFiles = (new GetCompetitionDefaultAudioAction($this->expectedFileTypes))->handle($audioFiles);

        return $this->handleCompCostAudioSwapout($competitionPhoneLine, $audioFiles);
    }

    protected function handleCompCostAudioSwapout(CompetitionPhoneLine $competitionPhoneLine, array $audioFiles): array
    {
        $cost = str_replace('.', '_', $competitionPhoneLine->cost); // normalize to '2_00', '2_50', etc.

        return collect($audioFiles)
            ->when(isset(self::COST_OVERRIDES[$cost]), function ($collection) use ($cost) {
                foreach (self::COST_OVERRIDES[$cost] as $target => $source) {
                    if (isset($collection[$source])) {
                        $collection[$target] = $collection[$source];
                    }
                }
                return $collection;
            })
            ->forget(self::OVERRIDE_KEYS_TO_REMOVE)
            ->all();
    }
}
