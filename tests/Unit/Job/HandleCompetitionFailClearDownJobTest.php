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
        PhoneBookEntry::factory(['phone_number'=>'441234567890', 'name' => 'bar'])->create();

        $activeCall = ActiveCall::factory(['phone_number'=>'441234567890'])->create();

        Participant::factory(['call_id'=>$activeCall->call_id])->create(); // this should be removed

        $dto = $this->getActiveCallDTO($activeCall);

        HandleCompetitionFailClearDownJob::dispatchSync($dto, 'foo');

        $this->assertCount(0, Participant::all());
        $this->assertCount(1, $failedEntries = FailedEntry::all());

        tap($failedEntries->first(), function (FailedEntry $failedEntry) use($dto) {
            $this->assertEquals($dto->competition_id, $failedEntry->competition_id);
            $this->assertEquals($dto->call_id, $failedEntry->call_id);
            $this->assertEquals($dto->competition_phone_number, $failedEntry->phone_number);
            $this->assertEquals($dto->caller_phone_number, $failedEntry->caller_phone_number);
            $this->assertEquals('foo', $failedEntry->reason);
            $this->assertEquals($dto->created_at, $failedEntry->call_start);
            $this->assertNotNull($failedEntry->call_end);
            $this->assertEquals($dto->round_start, $failedEntry->round_start);
            $this->assertEquals($dto->round_end, $failedEntry->round_end);
            $this->assertEquals('bar', $failedEntry->station_name);
        });

        $this->assertCount(0, ActiveCall::all());
    }
}
