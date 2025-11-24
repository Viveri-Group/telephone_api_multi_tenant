<?php

namespace App\Action\Competition;

use App\Action\PhoneBook\PhoneBookLookupAction;
use App\DTO\ActiveCall\ActiveCallDTO;
use App\Models\ActiveCall;
use App\Models\Participant;

class CreateParticipantFromActiveCallDTOAction
{
    public function handle(ActiveCallDTO $activeCallDTO)
    {
        $phoneBookEntry = (new PhoneBookLookupAction())->handle($activeCallDTO->competition_phone_number);

        $participant = Participant::create([
            'call_id' => $activeCallDTO->call_id,
            'call_start' => $activeCallDTO->created_at,
            'organisation_id' => $activeCallDTO->organisation_id,
            'competition_id' => $activeCallDTO->competition_id,
            'cli_presentation' => $activeCallDTO->cli_presentation,
            'competition_phone_number' => $activeCallDTO->competition_phone_number,
            'telephone' => $activeCallDTO->caller_phone_number,
            'round_start' => $activeCallDTO->round_start,
            'round_end' => $activeCallDTO->round_end,
            'station_name' => $phoneBookEntry?->name,
        ]);

        ActiveCall::find($activeCallDTO->id)?->update(['participant_id' => $participant->id]);

        return $participant;
    }
}
