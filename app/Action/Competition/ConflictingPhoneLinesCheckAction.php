<?php

namespace App\Action\Competition;

use App\Models\CompetitionPhoneLine;

class ConflictingPhoneLinesCheckAction
{
    public function __construct(public string $competitionId, public array $phoneNumbers)
    {
    }

    public function handle()
    {
        return CompetitionPhoneLine::with('competition')
        ->whereIn('phone_number', $this->phoneNumbers)
            ->where('competition_id', '<>', $this->competitionId)
            ->get()
            ->pluck('competition')
            ->unique('id')
            ->values();
    }
}
