<?php

namespace App\Action\Competition;

use App\Models\Competition;
use Illuminate\Support\Carbon;

class GetCompetitionCacheKeyAction
{
    public function handle(Competition $competition, Carbon $start, Carbon $end): string
    {
        $formattedStart = $start->format('Y_m_d__H_i_s');
        $formattedEnd = $end->format('Y_m_d__H_i_s');

        return "{$competition->id}_{$formattedStart}__{$formattedEnd}";
    }
}
