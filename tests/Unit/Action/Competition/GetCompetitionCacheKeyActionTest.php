<?php

namespace Tests\Unit\Action\Competition;

use App\Action\Competition\GetCompetitionCacheKeyAction;
use App\Action\Competition\GetCompetitionRoundsAction;
use App\Models\Competition;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GetCompetitionCacheKeyActionTest extends TestCase
{
    public function test_the_cache_key_is_returned()
    {
        $this->login();

        $competition = Competition::factory(
            [
                'start' => '2020-01-01 09:00:00',
                'end' => '2020-01-01 16:00:00',
            ]
        )->create();

        $roundInfo = app(GetCompetitionRoundsAction::class)->handle($competition);

        $round = $roundInfo[0] ?? null;

        if (!$round) {
            return;
        }

        $start = Carbon::parse(array_key_first($round));
        $end = Carbon::parse($round[$start->toDateTimeString()]);

        $cacheKey = app(GetCompetitionCacheKeyAction::class)->handle($competition, $start, $end);

        $this->assertSame(
            $cacheKey,
            $competition->id . '_2020_01_01__09_00_00__2020_01_01__16_00_00'
        );
    }
}
