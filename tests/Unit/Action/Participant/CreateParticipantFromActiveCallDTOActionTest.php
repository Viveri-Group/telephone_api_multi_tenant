<?php

namespace Tests\Unit\Action\Participant;

use App\Action\Competition\CreateParticipantFromActiveCallDTOAction;
use App\Models\ActiveCall;
use App\Models\Participant;
use App\Models\PhoneBookEntry;
use Tests\TestCase;

class CreateParticipantFromActiveCallDTOActionTest extends TestCase
{
    public function test_participant_is_created()
    {
        PhoneBookEntry::factory(['phone_number' => '441234567890', 'name' => 'bar'])->create();

        $activeCall = ActiveCall::factory([
            'phone_number' => '441234567890',
            'participant_id' => null,
        ])->create();

        $dto = $this->getActiveCallDTO($activeCall);

        $returnedParticipant = (new CreateParticipantFromActiveCallDTOAction())->handle($dto);

        $this->assertCount(1, $participants = Participant::all());
        $participant = $participants->first();

        $this->assertSame($returnedParticipant->id, $participant->id);

        tap($participant, function(Participant $participant) use($dto){
            $this->assertEquals($dto->call_id, $participant->call_id);
            $this->assertEquals($dto->created_at, $participant->call_start);
            $this->assertNull($participant->call_end);
            $this->assertEquals($dto->competition_id, $participant->competition_id);
            $this->assertEquals($dto->cli_presentation, $participant->cli_presentation);
            $this->assertEquals($dto->competition_phone_number, $participant->competition_phone_number);
            $this->assertEquals($dto->caller_phone_number, $participant->telephone);
            $this->assertEquals($dto->round_start, $participant->round_start);
            $this->assertEquals($dto->round_end, $participant->round_end);
            $this->assertSame('bar', $participant->station_name);
        });
    }
}
