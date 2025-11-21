<?php

namespace App\Action\PhoneBook;

use App\Models\CompetitionPhoneLine;

class PhoneBookEntryGetPhoneLinesAction
{
    public function handle(string $phoneNumber)
    {
        return CompetitionPhoneLine::where('phone_number', $phoneNumber)->get();
    }
}
