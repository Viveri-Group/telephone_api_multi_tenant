<?php

namespace Tests\Unit\Job;

use App\Jobs\HandleCompetitionClearDownSuccessJob;
use App\Models\ActiveCall;
use App\Models\Participant;
use App\Models\PhoneBookEntry;
use Tests\TestCase;

class HandleCompetitionClearDownSuccessJobTest extends TestCase
{
    public function test_job_works_as_expected_with_participant_already_created_and_linked_to_active_call()
    {
        PhoneBookEntry::factory(['phone_number' => '441234567890', 'name' => 'bar'])->create();

        $activeCall = ActiveCall::factory(['phone_number' => '441234567890'])->create();
        $participant = Participant::factory()->create(['call_id' => $activeCall->call_id]);
        $activeCall->update(['participant_id' => $participant->id]);

        $dto = $this->getActiveCallDTO($activeCall);

        HandleCompetitionClearDownSuccessJob::dispatchSync($dto);

        $this->assertCount(1, $participants = Participant::all());
        $this->assertCount(0, ActiveCall::all());

        tap($participants->first(), function (Participant $participant) use ($dto) {
            $this->assertNotNull($participant->call_end);
        });
    }

    public function test_job_works_as_expected_with_no_participant_already_linked_to_active_call()
    {
        PhoneBookEntry::factory(['phone_number' => '441234567890', 'name' => 'bar'])->create();

        $activeCall = ActiveCall::factory(['phone_number' => '441234567890'])->create();

        $dto = $this->getActiveCallDTO($activeCall);

        HandleCompetitionClearDownSuccessJob::dispatchSync($dto);

        $this->assertCount(1, $participants = Participant::all());
        $this->assertCount(0, ActiveCall::all());

        tap($participants->first(), function (Participant $participant) use ($dto) {
            $this->assertEquals($dto->call_id, $participant->call_id);
            $this->assertNotNull($participant->call_end);
        });
    }
}
