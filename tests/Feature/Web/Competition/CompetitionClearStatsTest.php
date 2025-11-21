<?php

namespace Tests\Feature\Web\Competition;

use App\Action\Competition\GetCompetitionCacheKeyAction;
use App\Action\Competition\GetCompetitionRoundsAction;
use App\Models\Competition;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CompetitionClearStatsTest extends TestCase
{
    public function test_it_clears_the_cached_stats()
    {
        $this->login();

        $competition = Competition::factory()->create();

        $roundInfo = app(GetCompetitionRoundsAction::class)->handle($competition);

        $round = $roundInfo[0] ?? null;

        if (!$round) {
            return;
        }

        $start = Carbon::parse(array_key_first($round));
        $end = Carbon::parse($round[$start->toDateTimeString()]);

        $cacheKey = app(GetCompetitionCacheKeyAction::class)->handle($competition, $start, $end);

        Cache::remember('competition_stats_' . $cacheKey, now()->addDay(), function () {
            return 'foo';
        });

        $this->assertTrue(Cache::has('competition_stats_' . $cacheKey));

        $this->get(route('web.competition.clear-stats-cache', $competition))
            ->assertRedirect(route('web.competition.show', $competition));

        $this->assertFalse(Cache::has('competition_stats_' . $cacheKey));
    }
}
