<?php

namespace Tests\Feature\API\ClearDown;

use App\Models\ActiveCall;
use App\Models\FailedEntry;
use App\Models\Participant;
use Tests\TestCase;

class ClearDownFailureTest extends TestCase
{
    public function test_endpoint_works_as_expected_with_failure()
    {
        $this->login();

        $activeCall = ActiveCall::factory()->create(['call_end' => null]);

        $this->postJson(route('active-call.clear-down', $activeCall), [
            'marker' => 'FOO_BAR_BAZ',
            'recordFileNum' => 123,
        ])->assertOk();

        $this->assertCount(1, $failedEntries = FailedEntry::all());
        $this->assertCount(0, Participant::all());

        $failedEntry = $failedEntries->first();

        tap($failedEntry, function ($failedEntry) use ($activeCall) {
            $this->assertEquals($activeCall->competition_id, $failedEntry->competition_id);
            $this->assertEquals($activeCall->call_id, $failedEntry->call_id);
            $this->assertEquals($activeCall->phone_number, $failedEntry->phone_number);
            $this->assertEquals($activeCall->caller_phone_number, $failedEntry->caller_phone_number);
            $this->assertEquals('FOO_BAR_BAZ', $failedEntry->reason);
            $this->assertEquals($activeCall->created_at->format('Y-m-d H:i:s'), $failedEntry->call_start->format('Y-m-d H:i:s'));
            $this->assertNotNull($failedEntry->call_end);
        });
    }

    public function test_endpoint_clears_up_false_participants()
    {
        $this->login();

        $activeCall = ActiveCall::factory()->create(['call_end' => null]);
        Participant::factory()->create(['call_id' => $activeCall->call_id]);

        $this->postJson(route('active-call.clear-down', $activeCall), [
            'marker' => 'FOO_BAR_BAZ',
            'recordFileNum' => 123,
        ])->assertOk();

        $this->assertCount(0, Participant::all());
    }
}
