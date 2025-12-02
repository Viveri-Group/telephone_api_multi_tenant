<?php

namespace Tests\Unit\Action\EntrantRoundCount;

use App\Action\EntrantRoundCount\GetEntrantRoundCountAction;
use App\Models\ActiveCall;
use App\Models\EntrantRoundCount;
use Tests\TestCase;

class GetEntrantRoundCountActionTest extends TestCase
{
    public function test_action_returns_count_as_expected()
    {
        $activeCall = ActiveCall::factory()->create();

        EntrantRoundCount::factory()->create([
            'hash' => hash('xxh128', "{$activeCall->round_start} {$activeCall->competition_id} {$activeCall->caller_phone_number}"),
            'total_entry_count' => 10,
        ]);

        $this->assertEqualsCanonicalizing(
            10,
            (new GetEntrantRoundCountAction())->handle($activeCall)
        );
    }

    public function test_action_returns_0_as_expected()
    {
        $activeCall = ActiveCall::factory()->create();

        $this->assertEqualsCanonicalizing(
            0,
            (new GetEntrantRoundCountAction())->handle($activeCall)
        );
    }
}
