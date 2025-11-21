<?php

namespace Tests\Feature\API\Competition\Statistics;

use App\Models\Competition;
use App\Models\Participant;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->expectedCount = 20;

        $this->competitionA = Competition::factory(['name' => 'Test Competition'])->hasPhoneLines(['phone_number' => '03000111111'])->create();

        Participant::factory([
            'competition_id' => $this->competitionA->id,
            'drawn_at' => null,
        ])->count($this->expectedCount)
            ->create();

        //excluded as already drawn
        Participant::factory([
            'competition_id' => $this->competitionA->id,
            'drawn_at' => now()
        ])->create();
    }

    public function test_getting_competition_statistics()
    {
        $this->get(route('competition.statistics.active-round', $this->competitionA))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('data.type', 'competition-statistics')
                    ->where('data.attributes.participant_count', $this->expectedCount)
                    ->where('data.attributes.competition_name', $this->competitionA->name)
                    ->where('data.attributes.competition_id', $this->competitionA->id);
            });
    }
}
