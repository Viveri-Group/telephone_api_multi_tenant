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

        FileDefault::factory()->create(['external_id' => 1, 'type' => CompetitionAudioType::INTRO]);
        FileDefault::factory()->create(['external_id' => 2, 'type' => CompetitionAudioType::CLI_READOUT_NOTICE]);
        FileDefault::factory()->create(['external_id' => 3, 'type' => CompetitionAudioType::DTMF_MENU]);
        FileDefault::factory()->create(['external_id' => 4, 'type' => CompetitionAudioType::DTMF_SUCCESS]);
        FileDefault::factory()->create(['external_id' => 5, 'type' => CompetitionAudioType::DTMF_FAIL]);
        FileDefault::factory()->create(['external_id' => 6, 'type' => CompetitionAudioType::COMPETITION_CLOSED]);
        FileDefault::factory()->create(['external_id' => 7, 'type' => CompetitionAudioType::TOO_MANY_ENTRIES]);
    }

    public function test_default_audio()
    {
        $audioFiles = (new GetCompetitionDefaultAudioAction(CompetitionAudioType::names()))->handle();

        $this->assertEqualsCanonicalizing(
            [
                "INTRO" => 1,
                "CLI_READOUT_NOTICE" => 2,
                "DTMF_MENU" => 3,
                "DTMF_SUCCESS" => 4,
                "DTMF_FAIL" => 5,
                "COMPETITION_CLOSED" => 6,
                "TOO_MANY_ENTRIES" => 7,
            ], $audioFiles);
    }

    public function test_default_audio_with___dtmf_success_sms_enabled()
    {
        FileDefault::factory()->create(['external_id' => 10, 'type' => CompetitionAudioType::DTMF_SUCCESS_SMS]);

        $audioFiles = (new GetCompetitionDefaultAudioAction(CompetitionAudioType::names()))->handle();

        $this->assertEqualsCanonicalizing(
            [
                "INTRO" => 1,
                "CLI_READOUT_NOTICE" => 2,
                "DTMF_MENU" => 3,
                "DTMF_SUCCESS" => 4,
                "DTMF_FAIL" => 5,
                "COMPETITION_CLOSED" => 6,
                "TOO_MANY_ENTRIES" => 7,
                'DTMF_SUCCESS_SMS' => 10
            ], $audioFiles);
    }
}
