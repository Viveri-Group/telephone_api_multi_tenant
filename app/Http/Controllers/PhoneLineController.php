<?php

namespace App\Http\Controllers;

use App\Action\PhoneBook\PhoneBookLookupAction;
use App\Action\PhoneLine\PhoneNumberCleanupAction;
use App\Http\Requests\PhoneLineRequest;
use App\Http\Resources\PhoneLineResource;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;

class PhoneLineController extends Controller
{
    public function store(PhoneLineRequest $request, Competition $competition)
    {
        $phoneNumber = (new PhoneNumberCleanupAction())->handle($request->input('phone_number'));

        $phoneBookEntry = (new PhoneBookLookupAction())->handle($phoneNumber);

        $newPhoneLine = $competition->phoneLines()->create([
            'phone_number' => $request->input('phone_number'),
            'cost' => $phoneBookEntry->cost
        ]);

        return new PhoneLineResource($newPhoneLine);
    }

    public function show(Competition $competition, CompetitionPhoneLine $phoneLine)
    {
        return new PhoneLineResource($phoneLine);
    }

    public function update(PhoneLineRequest $request, Competition $competition, CompetitionPhoneLine $phoneLine)
    {
        $phoneLine->update($request->validated());

        return new PhoneLineResource($phoneLine);
    }

    public function destroy(Competition $competition, CompetitionPhoneLine $phoneLine)
    {
        $phoneLine->delete();

        return response()->noContent();
    }
}
