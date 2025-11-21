<?php

namespace App\Action\Competition;

use App\Enums\CompetitionDateType;
use App\Models\Competition;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class GetCompetitionCurrentRoundAction
{
    public function handle(Competition $competition): ?array
    {
        $rounds = (new GetCompetitionRoundsAction())->handle($competition);

        foreach ($rounds as $round) {
            $start = Carbon::parse(key($round));
            $end = Carbon::parse(current($round));

            if (now()->between($start, $end)) {
                return $round;
            }
        }

        return null;
    }
}
