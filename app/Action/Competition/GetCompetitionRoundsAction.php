<?php

namespace App\Action\Competition;

use App\Models\Competition;

class GetCompetitionRoundsAction
{
    public function handle(Competition $competition): array
    {
        return [[$competition->start->format('Y-m-d H:i:s') => $competition->end->format('Y-m-d H:i:s')]];
    }
}
