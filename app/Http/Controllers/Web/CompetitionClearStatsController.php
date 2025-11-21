<?php

namespace App\Http\Controllers\Web;

use App\Action\Competition\GetCompetitionCacheKeyAction;
use App\Action\Competition\GetCompetitionRoundsAction;
use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CompetitionClearStatsController extends Controller
{
    public function __invoke(Competition $competition)
    {
        $roundInfo = app(GetCompetitionRoundsAction::class)->handle($competition);

        $round = $roundInfo[0] ?? null;

        if (!$round) {
            return;
        }

        $start = Carbon::parse(array_key_first($round));
        $end = Carbon::parse($round[$start->toDateTimeString()]);

        $cacheKey = app(GetCompetitionCacheKeyAction::class)->handle($competition, $start, $end);

        Cache::forget('competition_stats_' . $cacheKey);

        return redirect()
            ->route('web.competition.show', $competition);
    }
}
