<?php

namespace Tests\Unit\Console;

use App\Models\ActiveCall;
use App\Models\ActiveCallOrphan;
use App\Models\Competition;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ActiveCallClearUpCommandTest extends TestCase
{
    public function test_relevant_logs_are_removed()
    {
        Carbon::setTestNow('2004-04-30 09:10:00');

        $competition = Competition::factory()->create();

        $activeCallA = ActiveCall::factory(['call_id'=>'111','phone_number'=>'44333111222', 'caller_phone_number'=>'07000000111', 'created_at' => '2004-04-30 09:00:00', 'competition_id' => $competition->id, 'status'=>'foo'])->create(); //10 mins old
        $activeCallB = ActiveCall::factory(['call_id'=>'222','phone_number'=>'44333111222', 'caller_phone_number'=>'07000000222', 'created_at' => '2004-04-30 09:04:00', 'competition_id' => $competition->id, 'status'=>'bar'])->create(); //6 mins old
        $activeCallC = ActiveCall::factory(['call_id'=>'333','phone_number'=>'44333111222', 'caller_phone_number'=>'07000000333', 'created_at' => '2004-04-30 09:08:00', 'competition_id' => $competition->id, 'status'=>'baz'])->create(); //2 mins old
        $activeCallD = ActiveCall::factory(['call_id'=>'444','phone_number'=>'44333111222', 'caller_phone_number'=>'07000000444', 'created_at' => '2004-04-30 09:09:00', 'competition_id' => $competition->id, 'status'=>'bat'])->create(); //1 min old

        $this->assertCount(4, ActiveCall::all());

        $this->artisan('viveri:active-call-clear-up');

        $this->assertCount(1, $activeCalls = ActiveCall::all());
        $this->assertCount(3, $activeCallOrphans = ActiveCallOrphan::all());

        tap($activeCallOrphans, function (Collection $activeCallOrphans) use($activeCallA, $activeCallB) {
            $orphan1 = $activeCallOrphans->get(0);
            $this->assertSame($activeCallA->competition_id, $orphan1->competition_id);
            $this->assertEquals($activeCallA->call_id, $orphan1->call_id);
            $this->assertSame($activeCallA->phone_number, $orphan1->phone_number);
            $this->assertSame($activeCallA->caller_phone_number, $orphan1->caller_phone_number);
            $this->assertSame($activeCallA->status, $orphan1->status);
            $this->assertSame($activeCallA->created_at->toString(), $orphan1->original_call_time->toString());

            $orphan2 = $activeCallOrphans->get(1);
            $this->assertSame($activeCallB->competition_id, $orphan2->competition_id);
            $this->assertEquals($activeCallB->call_id, $orphan2->call_id);
            $this->assertSame($activeCallB->phone_number, $orphan2->phone_number);
            $this->assertSame($activeCallB->caller_phone_number, $orphan2->caller_phone_number);
            $this->assertSame($activeCallB->status, $orphan2->status);
            $this->assertSame($activeCallB->created_at->toString(), $orphan2->original_call_time->toString());
        });

        tap($activeCalls, function (Collection $activeCalls) use($activeCallD) {
            $activeCall1 = $activeCalls->get(0);

            $this->assertSame($activeCallD->caller_phone_number, $activeCall1->caller_phone_number);
        });
    }
}
