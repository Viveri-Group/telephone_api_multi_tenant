<?php

namespace Tests\Feature\API\Competition\SpecifyWinner;

use App\Models\Competition;
use App\Models\CompetitionDraw;
use App\Models\CompetitionWinner;
use App\Models\Participant;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SpecifyWinnerWholeTest extends TestCase
{
    public function setUp(): void
    {
        Carbon::setTestNow('2024-10-31 15:30:00');
        parent::setUp();

        $this->login();

        $this->winningPosition = 7;

        list($organisation, $phoneBookEntry, $this->competition, $phoneLine, $competitionNumber, $callerNumber) = $this->setCompetition();

        $competitionDraw = CompetitionDraw::factory([
            'competition_id' => $this->competition->id,
            'round_from' => $this->competition->start->format('Y-m-d'),
            'round_to' => $this->competition->end->format('Y-m-d'),
            'round_hash' => 'foo_round_hash',
        ])->create();

        $participants = Participant::factory([
            'competition_id' => $this->competition->id,
            'competition_phone_number' => $phoneLine->phone_number,
            'competition_draw_id' => $competitionDraw->id
        ])
            ->count(30)
            ->create();

        $this->winningParticipant = $participants->get($this->winningPosition - 1);
    }

    public function test_winner_can_be_set()
    {
        $this->assertCount(30, Participant::where('competition_id', $this->competition->id)->get());
        $this->assertCount(0, CompetitionWinner::where('competition_id', $this->competition->id)->get());

        $this->post(
            route('competition.specify-winner', $this->competition), [
                'position' => $this->winningPosition,
                'round_hash' => 'foo_round_hash'
            ]
        )->assertCreated();

        $this->assertCount(1, $winner = CompetitionWinner::where('competition_id', $this->competition->id)->get());

        tap($winner->first(), function (CompetitionWinner $winner) {
            $this->assertSame($this->winningParticipant->id, $winner->participant_id);
            $this->assertSame($this->winningParticipant->competition_id, $winner->competition_id);
            $this->assertEquals($this->winningParticipant->call_id, $winner->call_id);
            $this->assertSame(1, $winner->number_of_entries);
            $this->assertSame('foo_round_hash', $winner->round_hash);
            $this->assertSame($this->winningParticipant->competition_phone_number, $winner->competition_phone_number);
            $this->assertSame($this->winningParticipant->telephone, $winner->telephone);
            $this->assertSame($this->winningParticipant->call_start->format('Y-m-d H:i:s'), $winner->call_start);
            $this->assertSame($this->winningParticipant->call_end->format('Y-m-d H:i:s'), $winner->call_end);
        });
    }
}
