<?php

namespace Tests\Unit\Job;

use App\Jobs\HandleCompetitionFailClearDownJob;
use App\Models\ActiveCall;
use App\Models\FailedEntry;
use App\Models\Participant;
use App\Models\PhoneBookEntry;
use Tests\TestCase;

class HandleCompetitionFailClearDownJobTest extends TestCase
{
    public function test_job_works_as_expected()
    {
        list($organisation, $phoneBookEntry, $competition, $phoneLine, $competitionNumber, $callerNumber) = $this->setCompetition();

        $activeCall = ActiveCall::factory([
            'organisation_id' => $organisation->id,
            'competition_id' => $competition->id,
            'competition_phone_line_id' => $phoneLine->id,
            'phone_number' => $competitionNumber,
            'caller_phone_number' => $callerNumber,
        ])->create();

        $dto = $this->getActiveCallDTO($activeCall);

        HandleCompetitionFailClearDownJob::dispatchSync($dto, 'foo');

        $this->assertCount(0, Participant::all());
        $this->assertCount(1, $failedEntries = FailedEntry::all());

        tap($failedEntries->first(), function (FailedEntry $failedEntry) use ($dto, $phoneBookEntry) {
            $this->assertEquals($dto->organisation_id, $failedEntry->organisation_id);
            $this->assertEquals($dto->competition_id, $failedEntry->competition_id);
            $this->assertEquals($dto->call_id, $failedEntry->call_id);
            $this->assertEquals($dto->competition_phone_number, $failedEntry->phone_number);
            $this->assertEquals($dto->caller_phone_number, $failedEntry->caller_phone_number);
            $this->assertEquals('foo', $failedEntry->reason);
            $this->assertEquals($dto->created_at, $failedEntry->call_start);
            $this->assertNotNull($failedEntry->call_end);
            $this->assertEquals($dto->round_start, $failedEntry->round_start);
            $this->assertEquals($dto->round_end, $failedEntry->round_end);
            $this->assertEquals($phoneBookEntry->name, $failedEntry->station_name);
        });
    }
}
