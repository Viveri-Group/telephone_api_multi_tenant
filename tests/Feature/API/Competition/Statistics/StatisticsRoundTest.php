<?php

namespace Tests\Feature\API\Competition\Statistics;

use App\Models\Competition;
use App\Models\CompetitionDraw;
use App\Models\Participant;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StatisticsRoundTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->expectedCount = 2;

        $this->competitionA = Competition::factory(['name' => 'Test Competition'])->hasPhoneLines(['phone_number' => '03000111111'])->create();
        $this->competitionADraw = CompetitionDraw::factory(['competition_id'=>$this->competitionA->id, 'round_hash' => 'round_hash_foo'])->create();

        Participant::factory([ // included
            'competition_id' => $this->competitionA->id,
            'competition_draw_id' => $this->competitionADraw->id,
        ])->create();

        Participant::factory([ // included
            'competition_id' => $this->competitionA->id,
            'competition_draw_id' => $this->competitionADraw->id,
        ])->create();

        Participant::factory([ // excluded
            'competition_id' => $this->competitionA->id,
            'competition_draw_id' => null,
        ])->create();
    }

    public function test_getting_competition_statistics()
    {
        $this->post(route('competition.statistics.round', 'round_hash_foo'))
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
