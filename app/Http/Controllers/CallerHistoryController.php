<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallerHistoryRequest;
use App\Http\Resources\FailedEntryResource;
use App\Http\Resources\ParticipantResource;
use App\Models\FailedEntry;
use App\Models\Participant;

class CallerHistoryController extends Controller
{
    public function __invoke(CallerHistoryRequest $request)
    {
        $previousCompetitionId = null;
        $participantsEntryCount = 0;

        $participants = Participant::where('telephone', $request->input('caller_phone_number'))
            ->orderBy('competition_id')
            ->orderBy('id')
            ->with('competition')
            ->get()
            ->map(
                function (Participant $participant) use (&$previousCompetitionId, &$participantsEntryCount) {
                    $participantsEntryCount++;

                    $compMaxEntries = $participant->competition->max_paid_entries;

                    if ($previousCompetitionId === null) {
                        $isSameCompetition = false;
                    } else {
                        $isSameCompetition = $participant->competition_id === $previousCompetitionId;
                    }

                    $previousCompetitionId = $participant->competition_id;

                    if (!$isSameCompetition) {
                        $participantsEntryCount = 1;
                    }

                    $participant->entry_status = "Entry {$participantsEntryCount} of {$compMaxEntries}";

                    return $participant;
                }
            );


        $failedEntries = FailedEntry::where('caller_phone_number', $request->input('caller_phone_number'))->get();

        return response()->json([
            'participants' => ParticipantResource::collection($participants),
            'failed_entries' => FailedEntryResource::collection($failedEntries),
        ]);
    }
}
