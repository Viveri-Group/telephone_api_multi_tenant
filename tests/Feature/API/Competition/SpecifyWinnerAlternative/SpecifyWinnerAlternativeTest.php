<?php

namespace Tests\Feature\API\Competition\SpecifyWinnerAlternative;

use App\Models\Competition;
use App\Models\CompetitionWinnerAlt;
use App\Models\CompetitionDraw;
use App\Models\CompetitionWinner;
use App\Models\Participant;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SpecifyWinnerAlternativeTest extends TestCase
{
    public function setUp(): void
    {
        Carbon::setTestNow('2024-10-31 15:30:00');

        parent::setUp();

        $this->login();

        list($organisation, $phoneBookEntry, $this->competition, $this->phoneLine, $competitionNumber, $callerNumber) = $this->setCompetition();


        Participant::factory(['competition_id' => $this->competition->id, 'competition_phone_number'=> $this->phoneLine->phone_number, 'call_start' => '2024-10-02 09:00:00','call_end' => '2024-10-02 09:00:57', 'call_id'=>555])->count(10)->create();
    }

    public function test_validation()
    {
        $this->post(route('competition.specify-winner.alternative',$this->competition) )
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.source', 'position')
                    ->where('data.1.source', 'date_from')
                    ->where('data.2.source', 'date_to');
            });
    }

    public function test_out_of_bounds_exception()
    {
        $this->post(route('competition.specify-winner.alternative',$this->competition), [
            'position' => 999,
            'date_from' => '2024-10-01T09:00:00Z',
            'date_to' => '2024-10-05T17:00:00Z',
        ])->assertForbidden();
    }

    public function test_winner_can_be_set()
    {
        $this->assertCount(0, CompetitionWinnerAlt::all());

        $this->post(route('competition.specify-winner.alternative',$this->competition), [
            'position' => 4,
            'date_from' => '2024-10-01T09:00:00Z',
            'date_to' => '2024-10-05T17:00:00Z',
        ])->assertCreated()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->where('data.type', 'competition-alternate-winner')
                    ->has('data.id')
                    ->has('data.attributes.participant_id')
                    ->has('data.attributes.competition_id')
                    ->where('data.attributes.call_id', 555)
                    ->where('data.attributes.number_of_entries', 1)
                    ->where('data.attributes.date_from', '2024-10-01 09:00:00')
                    ->where('data.attributes.date_to', '2024-10-05 17:00:00')
                    ->where('data.attributes.competition_phone_number', $this->phoneLine->phone_number)
                    ->where('data.attributes.call_start', '2024-10-02 09:00:00')
                    ->where('data.attributes.call_end', '2024-10-02 09:00:57')
                    ->has('data.attributes.telephone')

                    ->has('data.attributes.created_at');
            });

        $this->assertCount(1, $winners = CompetitionWinnerAlt::all());

        tap($winners->first(), function(CompetitionWinnerAlt $winner){
            $participant = Participant::where('competition_id', $this->competition->id)->skip(4 - 1)->take(1)->first();

            $this->assertSame($participant->id, $winner->participant_id);
            $this->assertSame($participant->competition_id, $winner->competition_id);
            $this->assertEquals('555', $winner->call_id);
            $this->assertEquals(1, $winner->number_of_entries);
            $this->assertSame('2024-10-01 09:00:00', $winner->date_from);
            $this->assertSame('2024-10-05 17:00:00', $winner->date_to);
            $this->assertSame($participant->competition_phone_number, $winner->competition_phone_number);
            $this->assertSame($participant->telephone, $winner->telephone);
            $this->assertSame('2024-10-02 09:00:00', $winner->call_start);
        });
    }
}
