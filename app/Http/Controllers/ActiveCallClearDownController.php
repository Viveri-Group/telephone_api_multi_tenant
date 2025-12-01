<?php

namespace App\Http\Controllers;

use App\DTO\ActiveCall\ActiveCallDTO;
use App\Enums\ClearDownStatus;
use App\Enums\QueuePriority;
use App\Http\Requests\ActiveCallClearDownRequest;
use App\Jobs\HandleCompetitionClearDownSuccessJob;
use App\Jobs\HandleCompetitionFailClearDownJob;
use App\Models\ActiveCall;

class ActiveCallClearDownController extends Controller
{
    public function __invoke(ActiveCallClearDownRequest $request, ActiveCall $activeCall)
    {
        $activeCallDTO = $this->getDTO($activeCall);

        match($request->input('type')){
            ClearDownStatus::SUCCESS->value => HandleCompetitionClearDownSuccessJob::dispatch($activeCallDTO, $request->boolean('sms_offer_accepted'))->onQueue(QueuePriority::High->value),
            ClearDownStatus::FAIL->value => HandleCompetitionFailClearDownJob::dispatch($activeCallDTO, $request->input('reason'))->onQueue(QueuePriority::High->value)
        };

        $activeCall->delete();

        return response(status: 200);
    }

    protected function getDTO(ActiveCall $activeCall): ActiveCallDTO
    {
        return new ActiveCallDTO(
            $activeCall->id,
            $activeCall->organisation_id,
            $activeCall->competition_id,
            $activeCall->call_id,
            $activeCall->participant_id,
            $activeCall->competitionPhoneLine?->id,
            $activeCall->phone_number,
            $activeCall->caller_phone_number,
            $activeCall->status,
            $activeCall->round_start,
            $activeCall->round_end,
            now(),
            $activeCall->cli_presentation,
            null,
            $activeCall->created_at,
            $activeCall->updated_at
        );
    }
}
