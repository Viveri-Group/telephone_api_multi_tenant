<?php

namespace Tests\Unit\Console;

use App\Models\APIRequestLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class APIRequestLogPruneTest extends TestCase
{
    public function test_relevant_logs_are_removed()
    {
        Carbon::setTestNow('2004-04-30 09:00:00');

        APIRequestLog::factory(['created_at' => '2004-01-30 09:00:00'])->create();
        APIRequestLog::factory(['created_at' => '2004-03-01 09:00:00'])->create();
        $apiLogC = APIRequestLog::factory(['created_at' => '2004-04-01 09:00:00'])->create();
        $apiLogD = APIRequestLog::factory(['created_at' => '2004-04-30 09:00:00'])->create();

        $this->assertCount(4, APIRequestLog::all());

        $this->artisan('model:prune');

        $this->assertCount(2, $logs = APIRequestLog::all());

        tap($logs, function (Collection $logs) use($apiLogC, $apiLogD) {
            $log1 = $logs->get(0);
            $log2 = $logs->get(1);

            $this->assertSame($apiLogC->id, $log1->id);
            $this->assertSame($apiLogC->created_at->toDateTimeString(), $log1->created_at->toDateTimeString());

            $this->assertSame($apiLogD->id, $log2->id);
            $this->assertSame($apiLogD->created_at->toDateTimeString(), $log2->created_at->toDateTimeString());
        });
    }
}
