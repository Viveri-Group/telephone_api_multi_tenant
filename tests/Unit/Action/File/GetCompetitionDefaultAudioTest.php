<?php

namespace Tests\Unit\Action\File;

use App\Action\File\GetCompetitionDefaultAudioAction;
use App\Enums\CompetitionAudioType;
use App\Models\FileDefault;
use Tests\TestCase;

class GetCompetitionDefaultAudioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        FileDefault::factory()->create(['external_id' => 1, 'type' => CompetitionAudioType::PRE_EVENT]);
        FileDefault::factory()->create(['external_id' => 2, 'type' => CompetitionAudioType::POST_EVENT]);
        FileDefault::factory()->create(['external_id' => 3, 'type' => CompetitionAudioType::FN1]);
        FileDefault::factory()->create(['external_id' => 4, 'type' => CompetitionAudioType::FN2]);
        FileDefault::factory()->create(['external_id' => 5, 'type' => CompetitionAudioType::FN3]);
        FileDefault::factory()->create(['external_id' => 6, 'type' => CompetitionAudioType::FN4]);
        FileDefault::factory()->create(['external_id' => 7, 'type' => CompetitionAudioType::FN5]);
        FileDefault::factory()->create(['external_id' => 8, 'type' => CompetitionAudioType::FN6]);
        FileDefault::factory()->create(['external_id' => 9, 'type' => CompetitionAudioType::CALL_COST_WARNING]);
        FileDefault::factory()->create(['external_id' => 10, 'type' => CompetitionAudioType::CAPPING_MESSAGE]);
    }

    public function test_default_audio()
    {
        $audioFiles = (new GetCompetitionDefaultAudioAction(CompetitionAudioType::names()))->handle();

        $this->assertEqualsCanonicalizing(
            [
                "PRE_EVENT" => 1,
                "POST_EVENT" => 2,
                "FN1" => 3,
                "FN2" => 4,
                "FN3" => 5,
                "FN4" => 6,
                "FN5" => 7,
                "FN6" => 8,
                "CALL_COST_WARNING" => 9,
                "CAPPING_MESSAGE" => 10,
            ], $audioFiles);
    }
}
