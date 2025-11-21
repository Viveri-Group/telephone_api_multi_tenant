<?php

namespace App\Action\Competition;

use App\Models\Competition;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class CompetitionCheckForConflictingPhoneLines
{
    public function __construct(public Competition $competition)
    {
    }

    public function handle(): false|string
    {
        $phoneNumbers = $this->competition->phoneLines->pluck('phone_number')->toArray();

        if (empty($phoneNumbers)) {
            return false;
        }

        $conflictingCompetitions = App::makeWith(ConflictingPhoneLinesCheckAction::class, ['competitionId'=>$this->competition->id, 'phoneNumbers'=>$phoneNumbers])->handle();

        if ($conflictingCompetitions->isEmpty()) {
            return false;
        }

        $ids = $conflictingCompetitions->pluck('id')->implode(', ');
        return "Cannot update competition: conflicting phone lines exist in this timeframe (competition IDs: {$ids}).";
    }
}
