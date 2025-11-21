<?php

namespace Tests\Feature\API\Competition\Statistics;

use App\Models\Competition;
use App\Models\Participant;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StatisticsRangeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->expectedCount = 3;

        $this->competitionA = Competition::factory(['name' => 'Test Competition'])->hasPhoneLines(['phone_number' => '03000111111'])->create();

        Participant::factory([ // excluded - before time
            'competition_id' => $this->competitionA->id,
            'drawn_at' => null,
            'call_start' => '2024-01-10 09:20:00',
            'created_at' => '2024-01-10 09:20:00',
        ])->create();

        Participant::factory([ // included
            'competition_id' => $this->competitionA->id,
            'drawn_at' => null,
            'call_start' => '2024-01-10 09:30:00',
            'created_at' => '2024-01-10 09:30:00',
        ])->create();

        Participant::factory([ // included
            'competition_id' => $this->competitionA->id,
            'drawn_at' => null,
            'call_start' => '2024-01-11 10:30:00',
            'created_at' => '2024-01-11 10:30:00',
        ])->create();

        //drawn but still included
        Participant::factory([
            'competition_id' => $this->competitionA->id,
            'drawn_at' => now(),
            'call_start' => '2024-01-11 10:30:00',
            'created_at' => '2024-01-11 10:30:00',
        ])->create();
    }

    public function test_expects_dates_in_iso_8601_format()
    {
        $this->post(route('competition.statistics.range', $this->competitionA), ['date_from'=> '2024-01-10 09:30:00','date_to'=> '2024-01-11 10:30:00',])
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.source', 'date_from')
                    ->where('data.0.message', 'The date from field format is invalid.')
                    ->where('data.1.source', 'date_to')
                    ->where('data.1.message', 'The date to field format is invalid.');
            });;
    }

    public function test_getting_competition_statistics()
    {
        $this->post(route('competition.statistics.range', $this->competitionA), ['date_from'=> '2024-01-10T09:30:00Z','date_to'=> '2024-01-11T10:30:00Z',])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('data.type', 'competition-statistics')
                    ->where('data.attributes.participant_count', $this->expectedCount)
                    ->where('data.attributes.competition_name', $this->competitionA->name)
                    ->where('data.attributes.competition_id', $this->competitionA->id);
            });
    }

    public function test_ranges_wrong_way_round_fails()
    {
        $this->post(route('competition.statistics.range', $this->competitionA), ['date_from'=> '2024-01-11T10:30:00Z','date_to'=> '2024-01-10T09:30:00Z',])
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.message', 'The date from field must be a date before or equal to date to.')
                    ->where('data.0.source', 'date_from');
            });
    }
}
