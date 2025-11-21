<?php

namespace Tests\Feature\Web\Statistics;

use App\Action\Competition\CompetitionStatisticsAction;
use App\Action\Competition\GetCompetitionRoundsAction;
use App\Models\Competition;
use App\Models\Participant;
use App\Models\FailedEntry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Mockery;
use Tests\TestCase;

class CompetitionStatTest extends TestCase
{
    public function test_it_returns_empty_array_if_competition_has_not_started()
    {
        $competition = Competition::factory()->create([
            'start' => now()->addDay(),
            'end' => now()->addDays(2),
        ]);

        // Mock round info
        $this->mock(GetCompetitionRoundsAction::class, function ($mock) use ($competition) {
            $mock->shouldReceive('handle')->once()->with($competition)->andReturn([
                [now()->addDay()->toDateTimeString() => now()->addDays(2)->toDateTimeString()]
            ]);
        });

        $result = app(CompetitionStatisticsAction::class)->handle($competition);

        $this->assertEquals([], $result);
    }

    public function test_it_builds_and_caches_stats_if_competition_is_active()
    {
        $competition = Competition::factory()->create([
            'start' => now()->subDay(),
            'end' => now()->addDay(),
        ]);

        $start = now()->subDay()->startOfMinute();
        $end = now()->addDay()->startOfMinute();

        // Mock round info
        $this->mock(GetCompetitionRoundsAction::class, function ($mock) use ($competition, $start, $end) {
            $mock->shouldReceive('handle')->once()->with($competition)->andReturn([
                [$start->toDateTimeString() => $end->toDateTimeString()]
            ]);
        });

        Participant::factory()->count(3)->create(['competition_id' => $competition->id, 'call_start' => now()->subHours(6)]);
        FailedEntry::factory()->count(2)->create(['competition_id' => $competition->id, 'call_start' => now()->subHours(5), 'reason' => 'error']);

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(function ($key, $ttl, $callback) {
                return is_string($key) && $ttl instanceof Carbon && is_callable($callback);
            })
            ->andReturnUsing(fn($key, $ttl, $callback) => $callback());

        $result = app(CompetitionStatisticsAction::class)->handle($competition);

        $this->assertIsArray($result);
        $this->assertEquals(3, $result['success']['entries']);
        $this->assertEquals(2, $result['fail']['entries']);
        $this->assertEquals(['error' => 2], $result['fail']['reasons']);
        $this->assertEquals(5, $result['total']['entries']);
        $this->assertEquals(60.0, $result['total']['health']);
    }

    public function test_it_uses_long_term_cache_if_competition_is_finished()
    {
        Carbon::setTestNow(Carbon::parse('2025-07-30 08:38:00'));

        $competition = Competition::factory()->create([
            'start' => now()->subDays(10),
            'end' => now()->subDay(),
        ]);

        $start = now()->subDays(10)->startOfMinute();
        $end = now()->subDay()->startOfMinute();

        $expectedCacheKey = "competition_stats_{$competition->id}_{$start->format('Y_m_d__H_i_s')}__{$end->format('Y_m_d__H_i_s')}";

        $expectedResult = [
            'success' => ['entries' => 2, 'latest' => 'Some Date'],
            'fail' => ['entries' => 1, 'reasons' => ['oops' => 1], 'latest' => 'Another Date'],
            'total' => ['entries' => 3, 'health' => 66.67],
        ];

        // Mock round info
        $this->mock(GetCompetitionRoundsAction::class, function ($mock) use ($competition, $start, $end) {
            $mock->shouldReceive('handle')->once()->with($competition)->andReturn([
                [$start->toDateTimeString() => $end->toDateTimeString()]
            ]);
        });

        Cache::shouldReceive('remember')
            ->once()
            ->with(
                $expectedCacheKey,
                Mockery::on(fn($ttl) => $ttl->equalTo(now()->addDays(60))),
                Mockery::type('Closure')
            )
            ->andReturn($expectedResult);

        $result = app(CompetitionStatisticsAction::class)->handle($competition);

        $this->assertEquals($expectedResult, $result);

        Carbon::setTestNow(); // Reset time after test
    }

    public function test_it_handles_empty_data_scenario()
    {
        $competition = Competition::factory()->create([
            'start' => now()->subDays(2),
            'end' => now()->subDay(),
        ]);

        $start = now()->subDays(2)->startOfMinute();
        $end = now()->subDay()->startOfMinute();

        // Mock round info
        $this->mock(GetCompetitionRoundsAction::class, function ($mock) use ($competition, $start, $end) {
            $mock->shouldReceive('handle')->once()->with($competition)->andReturn([
                [$start->toDateTimeString() => $end->toDateTimeString()]
            ]);
        });

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(fn($key, $ttl, $callback) => $callback());

        $result = app(CompetitionStatisticsAction::class)->handle($competition);

        $this->assertEquals(0, $result['success']['entries']);
        $this->assertEquals('N/A', $result['success']['latest']);
        $this->assertEquals(0, $result['fail']['entries']);
        $this->assertEquals([], $result['fail']['reasons']);
        $this->assertEquals('N/A', $result['fail']['latest']);
        $this->assertEquals(0, $result['total']['entries']);
        $this->assertEquals(0, $result['total']['health']);
    }

    public function test_it_returns_empty_array_if_no_rounds_exist()
    {
        $competition = Competition::factory()->create();

        $this->mock(GetCompetitionRoundsAction::class, function ($mock) use ($competition) {
            $mock->shouldReceive('handle')->once()->with($competition)->andReturn([]);
        });

        $result = app(CompetitionStatisticsAction::class)->handle($competition);

        $this->assertEquals([], $result);
    }

    public function test_it_uses_first_round_if_multiple_rounds_exist()
    {
        Carbon::setTestNow(now());

        $competition = Competition::factory()->create();

        $firstRoundStart = now()->subDay();
        $firstRoundEnd = now()->addDay();

        $this->mock(GetCompetitionRoundsAction::class, function ($mock) use ($competition, $firstRoundStart, $firstRoundEnd) {
            $mock->shouldReceive('handle')->once()->with($competition)->andReturn([
                [
                    $firstRoundStart->toDateTimeString() => $firstRoundEnd->toDateTimeString()
                ],
                [
                    now()->subDays(5)->toDateTimeString() => now()->subDays(3)->toDateTimeString()
                ]
            ]);
        });

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(['stubbed']);

        $result = app(CompetitionStatisticsAction::class)->handle($competition);

        $this->assertEquals(['stubbed'], $result);

        Carbon::setTestNow();
    }

    public function test_it_generates_expected_cache_key_format()
    {
        $competition = Competition::factory()->create(['id' => 42]);

        $start = Carbon::parse('2025-01-01 10:00:00');
        $end = Carbon::parse('2025-01-01 12:00:00');

        $action = app(CompetitionStatisticsAction::class);
        $key = $action->getCacheKey($competition, $start, $end);

        $this->assertEquals('competition_stats_42_2025_01_01__10_00_00__2025_01_01__12_00_00', $key);
    }

    public function test_it_returns_cached_result_without_rebuilding_stats()
    {
        $competition = Competition::factory()->create();

        $resultFromCache = ['some' => 'cached_data'];

        $this->mock(GetCompetitionRoundsAction::class, function ($mock) use ($competition) {
            $mock->shouldReceive('handle')->once()->with($competition)->andReturn([
                [now()->subDay()->toDateTimeString() => now()->addDay()->toDateTimeString()]
            ]);
        });

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($resultFromCache);

        $result = app(CompetitionStatisticsAction::class)->handle($competition);

        $this->assertSame($resultFromCache, $result);
    }

}

