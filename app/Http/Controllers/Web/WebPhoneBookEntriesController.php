<?php

namespace App\Http\Controllers\Web;

use App\Action\PhoneLine\PhoneLineActiveCompetitionLookupAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompetitionResource;
use App\Http\Resources\Web\WebPhoneBookEntryResource;
use App\Http\Resources\Web\WebPhoneLineResource;
use App\Models\PhoneBookEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class WebPhoneBookEntriesController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Auth/PhoneBookEntries/Index',
            [
                'phoneBookEntries' => WebPhoneBookEntryResource::collection(PhoneBookEntry::all()),
            ]
        );
    }

    public function show(PhoneBookEntry $phoneBookEntry)
    {
        $lookupData = (new PhoneLineActiveCompetitionLookupAction())->handle($phoneBookEntry->phone_number);

        $lookupData = $lookupData->map(fn($item) => [
            'phone_line' => [
                'id' => $item->id,
                'number' => $item->phone_number,
            ],
            'competition' => [
                'id' => $item->competition->id,
                'name' => $item->competition->name,
                'start' => $item->competition->start,
                'end' => $item->competition->end,
            ],
        ]);

        return Inertia::render(
            'Auth/PhoneBookEntries/Show',
            [
                'phoneBookEntry' => $phoneBookEntry,
                'lookupPerformed' => now(),
                'lookupData' => $lookupData,
            ]
        );
    }
}
