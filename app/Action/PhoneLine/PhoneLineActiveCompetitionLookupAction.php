<?php

namespace App\Action\PhoneLine;
use App\Models\CompetitionPhoneLine;

class PhoneLineActiveCompetitionLookupAction
{
    public function handle(string $phoneNumber)
    {
        return CompetitionPhoneLine::withWhereHas('competition', fn($query) => $query
            ->where('start', '<=', now())
            ->where('end', '>=', now())
        )
            ->where('phone_number', $phoneNumber)
            ->get()
            ->filter(fn (CompetitionPhoneLine $phoneLine) => $phoneLine->competition->isOpen);
    }
}
