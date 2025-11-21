<?php

namespace Tests\Feature\API\ClearDown;

use App\Jobs\HandleCompetitionClearDownSuccessJob;
use App\Jobs\NotifyEndOfCallSQS;
use App\Models\ActiveCall;
use App\Models\FailedEntry;
use App\Models\Participant;
use Illuminate\Support\Facades\Bus;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ClearDownSuccessTest extends TestCase
{
    public function test_endpoint_works_as_expected_without_a_participant()
    {
        $this->login();

        $activeCall = ActiveCall::factory()->create(['call_end' => null]);

        $this->postJson(route('active-call.clear-down', $activeCall), [
            'marker' => 'COMP_OPEN_COMPLETE',
            'recordFileNum' => 123,
        ])->assertOk();

        $this->assertCount(1, $participants = Participant::all());

        $participant = $participants->first();

        tap($participant, function ($participant) use ($activeCall) {
            $this->assertEquals($activeCall->competition_id, $participant->competition_id);
            $this->assertEquals($activeCall->call_id, $participant->call_id);
            $this->assertEquals($activeCall->phone_number, $participant->competition_phone_number);
            $this->assertEquals($activeCall->caller_phone_number, $participant->telephone);
            $this->assertEquals($activeCall->created_at->format('Y-m-d H:i:s'), $participant->call_start->format('Y-m-d H:i:s'));
            $this->assertNotNull($participant->call_end);
            $this->assertSame(123, $participant->audio_file_number);
        });

        $this->assertCount(0, FailedEntry::all());
    }

    public function test_endpoint_works_as_expected_with_a_participant()
    {
        $this->login();

        $participant = Participant::factory()->create();
        $activeCall = ActiveCall::factory()->create(['participant_id' => $participant->id, 'call_end' => null]);

        $this->postJson(route('active-call.clear-down', $activeCall),[
            'marker' => 'COMP_OPEN_COMPLETE',
            'recordFileNum' => 123,
        ])->assertOk();

        $this->assertCount(1, $participants = Participant::all());

        tap($participants->first(), function ($participant) use ($activeCall) {
            $this->assertNotNull($participant->call_end);
        });

        $this->assertCount(0, FailedEntry::all());
    }

    public function test_paid_and_free_entries_get_call_end_set()
    {
        $this->login();

        $participantPaidEntry = Participant::factory()->create(['is_free_entry' => false]);
        $activeCall = ActiveCall::factory()->create(['participant_id' => $participantPaidEntry->id, 'call_end' => null, ]);
        $participantFreeEntry = Participant::factory()->create(['call_id' => $activeCall->call_id]);

        $this->postJson(route('active-call.clear-down', $activeCall),[
            'marker' => 'COMP_OPEN_COMPLETE',
            'recordFileNum' => 123,
        ])->assertOk();

        $this->assertCount(2, Participant::all());

        $participantPaidEntry->refresh();
        $participantFreeEntry->refresh();

        $this->assertNotNull($participantPaidEntry->call_end);
        $this->assertNotNull($participantFreeEntry->call_end);

        $this->assertCount(0, FailedEntry::all());
    }

    public function test_no_audio_file_is_sent_back()
    {
        $this->login();

        $activeCall = ActiveCall::factory()->create(['call_end' => null]);

        $this->postJson(route('active-call.clear-down', $activeCall), [
            'marker' => 'COMP_OPEN_COMPLETE',
            'recordFileNum' => 0,
        ])->assertOk();

        $participant = Participant::first();

        $this->assertNull($participant->audio_file_number);
    }

    public function test_validation()
    {
        $this->login();

        $activeCall = ActiveCall::factory()->create();

        $this->postJson(route('active-call.clear-down', $activeCall))
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.source', 'marker')
                    ->where('data.1.source', 'recordFileNum')
                    ->etc();
            });
    }

    #[DataProvider('markerSuccessDataProvider')]
    public function test_success_markers(string $marker)
    {
        Bus::fake();

        $this->login();

        $participant = Participant::factory()->create();
        $activeCall = ActiveCall::factory()->create(['participant_id' => $participant->id, 'call_end' => null]);

        $this->postJson(route('active-call.clear-down', $activeCall),[
            'marker' => $marker,
            'recordFileNum' => 123,
        ])->assertOk();

        $this->assertCount(1, $participants = Participant::all());

        tap($participants->first(), function ($participant) use ($activeCall) {
            $this->assertNotNull($participant->call_end);
        });

        $this->assertCount(0, FailedEntry::all());

        Bus::assertDispatched(HandleCompetitionClearDownSuccessJob::class);
    }

    public static function markerSuccessDataProvider(): array
    {
        return [
            'COMP_OPEN_ANSWERED' => [ 'marker' => 'COMP_OPEN_ANSWERED'],
            'COMP_OPEN_RECORDING' => [ 'marker' => 'COMP_OPEN_RECORDING'],
            'COMP_OPEN_COMPLETE' => [ 'marker' => 'COMP_OPEN_COMPLETE'],
            'EARLY_HANGUP_COMP_OPEN_RECORDING' => [ 'marker' => 'EARLY_HANGUP_COMP_OPEN_RECORDING'],
            'ABORTED_COMP_OPEN_RECORDING' => [ 'marker' => 'ABORTED_COMP_OPEN_RECORDING'],
            'ABORTED_COMP_OPEN_ANSWERED' => [ 'marker' => 'ABORTED_COMP_OPEN_ANSWERED'],
            'EARLY_HANGUP_COMP_OPEN_ANSWERED' => [ 'marker' => 'EARLY_HANGUP_COMP_OPEN_ANSWERED'],
        ];
    }
}
