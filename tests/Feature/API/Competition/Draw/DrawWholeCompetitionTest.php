<?php

namespace Tests\Feature\API\Competition\Draw;

use App\Jobs\MarkParticipantsAsDrawnJob;
use App\Models\Competition;
use App\Models\CompetitionDraw;
use App\Models\Participant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class DrawWholeCompetitionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2024-01-01 09:00:00');

        $this->login();

        list($this->organisation, $this->phoneBookEntry, $this->competition, $this->phoneLine, $this->competitionNumber, $this->callerNumber) = $this->setCompetition();

        $this->competition->update(['end' => now()->subMinute()]);

        Participant::factory([
            'competition_id' => $this->competition->id,
            'competition_phone_number' => $this->phoneLine->phone_number,
            'call_start' => now()->subDays(2),
        ])->count(2)->create();

        $competitionB = Competition::factory()->hasPhoneLines(['phone_number' => '03000111111'])->create();
        Participant::factory(['competition_id' => $competitionB->id])->count(10)->create();
    }

    public function test_running_draw_calls_job_as_expected()
    {
        Bus::fake();

        $this->post(route('competition.mark-as-drawn',
            [
                'competition' => $this->competition,
                'drawn_by' => 'bar',
            ]
        ))->assertNoContent();

        Bus::assertDispatched(MarkParticipantsAsDrawnJob::class,  function (MarkParticipantsAsDrawnJob $job) {
            $this->assertSame($this->competition->id, $job->competition->id);
            $this->assertSame('bar', $job->drawnBy);

            return true;
        });
    }

    public function test_running_draw()
    {
        $this->assertCount(12, Participant::whereNull('competition_draw_id')->whereNull('drawn_at')->get());
        $this->assertCount(0, CompetitionDraw::all());

        $this->post(route('competition.mark-as-drawn',
            [
                'competition' => $this->competition,
                'drawn_by' => 'bar',
            ]
        ))->assertNoContent();

        $this->assertCount(1, $competitionDraw = CompetitionDraw::all());
        $this->assertCount(2, $spentEntries = Participant::where('competition_id', $this->competition->id)->whereNotNull('drawn_at')->get());
        $this->assertCount(10, Participant::whereNull('competition_draw_id')->whereNull('drawn_at')->get());

        $competitionDraw = $competitionDraw->first();

        tap($competitionDraw, function (CompetitionDraw $draw) {
            $this->assertSame($this->competition->id, $draw->competition_id);
            $this->assertSame($this->competition->type, $draw->competition_type);
            $this->assertSame('bar', $draw->drawn_by);
        });

        tap($spentEntries, function (Collection $entries) use ($competitionDraw) {
            $entryA = $entries->get(0);
            $entryB = $entries->get(1);

            $this->assertSame($this->competition->id, $entryA->competition_id);
            $this->assertSame($this->phoneLine->phone_number, $entryA->competition_phone_number);
            $this->assertSame($competitionDraw->id, $entryA->competition_draw_id);
            $this->assertNotNull($entryA->drawn_at);

            $this->assertSame($this->competition->id, $entryB->competition_id);
            $this->assertSame($this->phoneLine->phone_number, $entryB->competition_phone_number);
            $this->assertNotNull($entryB->competition_draw_id);
            $this->assertSame($competitionDraw->id, $entryB->competition_draw_id);
            $this->assertNotNull($entryB->drawn_at);
        });
    }

    public function test_cant_draw_a_competition_that_has_been_drawn()
    {
        $this->assertCount(12, Participant::whereNull('competition_draw_id')->whereNull('drawn_at')->get());

        CompetitionDraw::factory()->create([
            'competition_id' => $this->competition->id,
            'round_from' => $this->competition->start->format('Y-m-d'),
            'round_to' => $this->competition->end->format('Y-m-d'),
        ]);

        $this->post(route('competition.mark-as-drawn', $this->competition))->assertBadRequest();

        $this->assertCount(12, Participant::whereNull('competition_draw_id')->whereNull('drawn_at')->get());

        $this->assertCount(1, CompetitionDraw::all());
    }

    public function test_cant_be_drawn_before_end_date()
    {
        $this->competition->update(['end' => now()->addMinutes(10)]);

        $this->assertCount(12, Participant::whereNull('competition_draw_id')->whereNull('drawn_at')->get());

        $this->post(route('competition.mark-as-drawn', $this->competition))->assertUnauthorized();

        $this->assertCount(12, Participant::whereNull('competition_draw_id')->whereNull('drawn_at')->get());

        $this->assertCount(0, CompetitionDraw::all());
    }
}
