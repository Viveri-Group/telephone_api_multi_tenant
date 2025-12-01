<?php

namespace App\Action\Competition;

use App\Action\EntrantRoundCount\GetEntrantRoundCountAction;
use App\Action\PhoneBook\PhoneBookEntryExistsAction;
use App\DTO\Competition\CompetitionPreCheckRequestDTO;
use App\Enums\ResponseStatus;
use App\Exceptions\CallerExceededMaxEntriesHTTPException;
use App\Exceptions\CompetitionClosedHTTPException;
use App\Exceptions\NoActiveCompetitionButCompetitionNumberKnownHTTPException;
use App\Exceptions\NoActiveCompetitionsHTTPException;
use App\Http\Resources\CompetitionCapacityCheckResource;
use App\Http\Resources\CompetitionCapacityCheckWithActivePhoneLineResource;
use App\Http\Resources\CompetitionResource;
use App\Models\ActiveCall;
use App\Models\CompetitionPhoneLine;
use App\Models\Participant;
use Illuminate\Support\Facades\Cache;
use Throwable;

class CompetitionPreCheckAction
{
    /**
     * @throws Throwable
     */
    public function handle(
        CompetitionPreCheckRequestDTO $requestDetails,
        ?CompetitionPhoneLine          $phoneLine,
        string                        $responseType,
        $recordActiveCall = true
    ): CompetitionResource|CompetitionCapacityCheckResource|CompetitionCapacityCheckWithActivePhoneLineResource
    {
        $callerNumber = $requestDetails->callerPhoneNumber;
        $competitionPhoneNumberIsKnown = (new PhoneBookEntryExistsAction())->handle($requestDetails->competitionPhoneNumber);

        throw_if(
            !$phoneLine && !$competitionPhoneNumberIsKnown,
            new NoActiveCompetitionsHTTPException(
                400,
                'No competitions associated with this phone line and number not recognised.',
                [
                    'competition_id' => null,
                    'status' => ResponseStatus::REJECT_CALLER->value,
                    'active_call_id' => null,
                    'sms_offer_enabled' => null
                ])
        );

        if ($recordActiveCall) {
            $activeCall = ActiveCall::create([
                'call_id' => $requestDetails->callID,
                'phone_number' => $requestDetails->competitionPhoneNumber,
                'caller_phone_number' => $callerNumber,
            ]);
        }

        throw_if(
            !$phoneLine && $competitionPhoneNumberIsKnown,
            new NoActiveCompetitionButCompetitionNumberKnownHTTPException(
                200,
                'Competition is closed',
                [
                    'competition_id' => null,
                    'status' => ResponseStatus::CLOSED->value,
                    'active_call_id' => $recordActiveCall ? $activeCall->id : null,
                    'sms_offer_enabled' => null
                ])
        );

        $activePhoneLine = $phoneLine;
        $activeCompetition = $activePhoneLine->competition;

        $currentRound = Cache::remember(
            "competition_active_round__{$activePhoneLine->phone_number}__{$activePhoneLine->competition_id}",
            now()->addSeconds(60),
            fn() => (new GetCompetitionCurrentRoundAction())->handle($activePhoneLine->competition)
        );

        if ($recordActiveCall) {
            $data = [];

            if($currentRound){
                $data['round_start'] = key($currentRound);
                $data['round_end'] = current($currentRound);
            }

            $activeCall->update([
                'competition_phone_line_id' => $activePhoneLine->id,
                'competition_id' => $activePhoneLine->competition_id,
                ...$data
            ]);
        }

        if($currentRound) {
            if ($recordActiveCall) {
                //quicker method preferred by realtime check
                $entriesCount = (new GetEntrantRoundCountAction())->handle($activeCall);
            }else {
                $entriesCount = Participant::where('telephone', $callerNumber)
                    ->where('competition_id', $activePhoneLine->competition_id)
                    ->whereBetween('created_at', [key($currentRound), current($currentRound)])
                    ->count();
            }

            if ($entriesCount >= $activeCompetition->max_entries) {
                throw_if(
                    true,
                    new CallerExceededMaxEntriesHTTPException(
                        406,
                        'Participant has exceeded allowed number of entries.',
                        [
                            'competition_id' => $activeCompetition->id,
                            'active_phone_line' => $activePhoneLine,
                            'status' => ResponseStatus::TOO_MANY->value,
                            'active_call_id' => $recordActiveCall ? $activeCall->id : null,
                            'sms_offer_enabled' => $activeCompetition->sms_offer_enabled,
                        ])
                );
            }
        }

        $activeCompetition->isOpen;

        throw_if(
            !$activeCompetition->isOpen,
            new CompetitionClosedHTTPException(
                200,
                'Competition is closed',
                [
                    'competition' => $activeCompetition,
                    'active_phone_line' => $activePhoneLine,
                    'status' => ResponseStatus::CLOSED->value,
                    'active_call_id' => $recordActiveCall ? $activeCall->id : null,
                    'sms_offer_enabled' =>$activeCompetition->sms_offer_enabled,
                ])
        );

        return match ($responseType) {
            CompetitionCapacityCheckResource::class => new CompetitionCapacityCheckResource($activeCompetition, ['status' => 'OPEN', 'active_call_id' => $activeCall->id, 'sms_offer_enabled' => $activeCompetition->sms_offer_enabled]),
            CompetitionCapacityCheckWithActivePhoneLineResource::class => new CompetitionCapacityCheckWithActivePhoneLineResource($activePhoneLine, ['status' => 'OPEN', 'active_call_id' => $activeCall->id, 'sms_offer_enabled' => $activeCompetition->sms_offer_enabled, 'entry_count'=>$entriesCount]),
            default => new CompetitionResource($activeCompetition),
        };
    }
}
