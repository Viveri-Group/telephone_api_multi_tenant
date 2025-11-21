<?php

namespace App\Action\Competition;

use Illuminate\Support\Carbon;

class CompetitionIsOpenCheckAction
{
    public function handle(array $rounds): bool
    {
        $currentTime = now();

        $isInOpenState = false;

        foreach ($rounds as $range) {
            foreach ($range as $openFrom => $openUntil) {
                $openFromTime = Carbon::parse($openFrom);
                $openUntilTime = Carbon::parse($openUntil);

                // Check if the date falls within this range.
                if ($currentTime->between($openFromTime, $openUntilTime)) {
                    $isInOpenState = true;
                    break 2; // Exit both loops since we found a match.
                }
            }
        }

        return $isInOpenState;
    }
}
