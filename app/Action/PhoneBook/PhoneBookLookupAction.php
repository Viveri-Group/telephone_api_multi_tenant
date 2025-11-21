<?php

namespace App\Action\PhoneBook;

use App\Models\PhoneBookEntry;

class PhoneBookLookupAction
{
    public function handle(string $competitionPhoneNumber): null|PhoneBookEntry
    {
        $phoneBookEntries = (new GetPhoneBookEntriesAction())->handle();

        return $phoneBookEntries->filter(fn(PhoneBookEntry $entry) => $entry->phone_number === $competitionPhoneNumber)->first();
    }
}
