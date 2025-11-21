<?php

namespace Tests\Unit\Console;

use App\Models\ActiveCallOrphan;
use App\Models\APIRequestLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ActiveCallOrphansPruneTest extends TestCase
{
    public function test_relevant_logs_are_removed()
    {
        Carbon::setTestNow('2020-04-30 09:00:00');

        ActiveCallOrphan::factory(['created_at' => '2020-01-30 09:00:00'])->create();
        ActiveCallOrphan::factory(['created_at' => '2020-03-01 09:00:00'])->create();
        $orphanC = ActiveCallOrphan::factory(['created_at' => '2020-04-01 09:00:00'])->create();
        $orphanD = ActiveCallOrphan::factory(['created_at' => '2020-04-30 09:00:00'])->create();

        $this->assertCount(4, ActiveCallOrphan::all());

        $this->artisan('model:prune');

        $this->assertCount(2, $logs = ActiveCallOrphan::all());

        tap($logs, function (Collection $logs) use($orphanC, $orphanD) {
            $log1 = $logs->get(0);
            $log2 = $logs->get(1);

            $this->assertSame($orphanC->id, $log1->id);
            $this->assertSame($orphanC->created_at->toDateTimeString(), $log1->created_at->toDateTimeString());

            $this->assertSame($orphanD->id, $log2->id);
            $this->assertSame($orphanD->created_at->toDateTimeString(), $log2->created_at->toDateTimeString());
        });
    }
}
