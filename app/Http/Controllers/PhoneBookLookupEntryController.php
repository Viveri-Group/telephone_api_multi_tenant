<?php

namespace App\Http\Controllers;

use App\Action\PhoneLine\PhoneLineActiveCompetitionLookupAction;
use App\Http\Resources\PhoneBookLookupEntryResource;
use App\Models\PhoneBookEntry;

class PhoneBookLookupEntryController extends Controller
{
    public function __invoke(PhoneBookEntry $phoneBookEntry)
    {
        $lookupData = (new PhoneLineActiveCompetitionLookupAction())->handle($phoneBookEntry->phone_number);

        if($lookupData->count() > 1){
            $competitionIds = $lookupData->pluck('competition_id')->implode(',');

            return response()->json([
                'error' => "Number {$phoneBookEntry->phone_number} points to multiple competitions: ({$competitionIds})",
            ], 409);
        }

        if($lookupData->isEmpty()){
            return response()->json();
        }

        return new PhoneBookLookupEntryResource($lookupData->first());
    }
}
