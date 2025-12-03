<?php

namespace Tests\Unit\Job;

use App\Jobs\HandleCompetitionClearDownSuccessJob;
use App\Models\ActiveCall;
use App\Models\Competition;
use App\Models\EntrantRoundCount;
use App\Models\FailedEntry;
use App\Models\Participant;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class HandleCompetitionSuccessClearDownJobTest extends TestCase
{
    public function test_success_is_handles_as_expected()
    {
        list($organisation, $phoneBookEntry, $competition, $phoneLine, $competitionNumber, $callerNumber) = $this->setCompetition();

        $activeCall = ActiveCall::factory([
            'organisation_id' => $organisation->id,
            'competition_id' => $competition->id,
            'competition_phone_line_id' => $phoneLine->id,
            'phone_number' => $competitionNumber,
            'caller_phone_number' => $callerNumber,
            'call_id' => 300,
            'created_at' => '2024-01-01 09:00:00',
        ])->create();

        $activeCallDTO = $this->getActiveCallDTO($activeCall);

        (new HandleCompetitionClearDownSuccessJob($activeCallDTO, false))->handle();

        $this->assertCount(1, $participants = Participant::all());
        $this->assertCount(0, FailedEntry::all());

        tap($participants->first(), function (Participant $participant) use ($competition, $organisation, $phoneLine, $callerNumber) {
            $this->assertEquals($organisation->id, $participant->organisation_id);
            $this->assertEquals($competition->id, $participant->competition_id);

            $this->assertEquals(300, $participant->call_id);
            $this->assertSame($phoneLine->phone_number, $participant->competition_phone_number);
            $this->assertSame($callerNumber, $participant->telephone);
            $this->assertSame('2024-01-01 09:00:00', $participant->call_start->format('Y-m-d H:i:s'));
        });
    }

    public function test_success_job_performs_max_entries_count_when_caller_has_1_attempt_left()
    {
        Carbon::setTestNow('2024-01-02 10:30:00');

        list($organisation, $phoneBookEntry, $competition, $phoneLine, $competitionNumber, $callerNumber) = $this->setCompetition();

        $roundCount = EntrantRoundCount::factory([
            'hash' => hash('xxh128', "{$competition->start} {$competition->id} {$callerNumber}"),
            'competition_id' => $competition->id,
            'caller_number' => $callerNumber,
            'total_entry_count' => 3,
        ])->create();

        $activeCall = ActiveCall::factory([
            'organisation_id' => $organisation->id,
            'competition_id' => $competition->id,
            'competition_phone_line_id' => $phoneLine->id,
            'phone_number' => $competitionNumber,
            'caller_phone_number' => $callerNumber,
            'call_id' => 300,
            'created_at' => '2024-01-02 10:30:00',
            'round_start' => $competition->start,
        ])->create();

        $activeCallDTO = $this->getActiveCallDTO($activeCall);

        (new HandleCompetitionClearDownSuccessJob($activeCallDTO, false))->handle();

        $this->assertCount(1, $participants = Participant::all());
        $this->assertCount(0, FailedEntry::all());

        tap($participants->first(), function (Participant $participant) use ($competition, $callerNumber, $phoneLine) {
            $this->assertSame($competition->id, $participant->competition_id);
            $this->assertEquals(300, $participant->call_id);
            $this->assertSame($phoneLine->phone_number, $participant->competition_phone_number);
            $this->assertSame($callerNumber, $participant->telephone);
            $this->assertSame('2024-01-02 10:30:00', $participant->call_start->format('Y-m-d H:i:s'));
        });

        $roundCount->refresh();
        $this->assertEquals(4, $roundCount->total_entry_count);
    }

    public function test_success_job_performs_max_entries_count_and_terminates_as_failed_entry_instead()
    {
        Carbon::setTestNow('2024-01-02 10:30:00');

        list($organisation, $phoneBookEntry, $competition, $phoneLine, $competitionNumber, $callerNumber) = $this->setCompetition();

        $roundCount = EntrantRoundCount::factory([
            'hash' => hash('xxh128', "{$competition->start} {$competition->id} {$callerNumber}"),
            'competition_id' => $competition->id,
            'caller_number' => $callerNumber,
            'total_entry_count' => 5,
        ])->create();

        $activeCall = ActiveCall::factory([
            'organisation_id' => $organisation->id,
            'competition_id' => $competition->id,
            'competition_phone_line_id' => $phoneLine->id,
            'phone_number' => $competitionNumber,
            'caller_phone_number' => $callerNumber,
            'call_id' => 300,
            'created_at' => '2024-01-02 10:30:00',
            'round_start' => $competition->start,
        ])->create();

        $activeCallDTO = $this->getActiveCallDTO($activeCall);

        (new HandleCompetitionClearDownSuccessJob($activeCallDTO, false))->handle();

        $this->assertCount(0, Participant::all());
        $this->assertCount(1, $failedEntries = FailedEntry::all());

        tap($failedEntries->first(), function (FailedEntry $failedEntry) use ($activeCallDTO, $phoneBookEntry) {
            $this->assertEquals($activeCallDTO->organisation_id, $failedEntry->organisation_id);
            $this->assertEquals($activeCallDTO->competition_id, $failedEntry->competition_id);
            $this->assertEquals($activeCallDTO->call_id, $failedEntry->call_id);
            $this->assertEquals($activeCallDTO->competition_phone_number, $failedEntry->phone_number);
            $this->assertEquals($activeCallDTO->caller_phone_number, $failedEntry->caller_phone_number);
            $this->assertEquals('TOO_MANY', $failedEntry->reason);
            $this->assertEquals($activeCallDTO->created_at, $failedEntry->call_start);
            $this->assertNotNull($failedEntry->call_end);
            $this->assertEquals($activeCallDTO->round_start, $failedEntry->round_start);
            $this->assertEquals($activeCallDTO->round_end, $failedEntry->round_end);
            $this->assertEquals($phoneBookEntry->name, $failedEntry->station_name);
        });

        $roundCount->refresh();
        $this->assertEquals(5, $roundCount->total_entry_count);
    }
}
