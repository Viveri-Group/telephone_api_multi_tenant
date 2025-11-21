<?php

namespace App\Action\PhoneBook;

use App\Models\PhoneBookEntry;

class PhoneBookEntryExistsAction
{
    public function handle(string $competitionPhoneNumber): bool
    {
        $phoneBookEntries = (new GetPhoneBookEntriesAction())->handle();

        return $phoneBookEntries->contains(fn(PhoneBookEntry $entry) => $entry->phone_number === $competitionPhoneNumber);
    }
}
